<?php
namespace App\Models;

use App\Enums\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollmentID',
        'student_id',
        'batch_id',
        'invoice_id',
        'has_installment_plan',
        'installment_id',
        'enrollment_payment_amount',
        'discount_percentage',
        'discounted_payment_amount',
        'additional_discount_amount',
        'total_payment_amount',
    ];

    public function installment()
    {

    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function course()
    {
        return $this->batch->belongsTo(Course::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Payment::class);
    }

    public function getInstallmentDownPayment($installment_id)
    {
        return Installment::find($installment_id)->down_payment;
    }

    public function getCoursePrice()
    {
        return $this->batch->course->price;
    }

    public function getInstallmentCoursePrice($installmentId)
    {
        return Installment::find($installmentId)->installment_price;
    }

    // Method to get the number of installment months
    public function getInstallmentMonth($installmentId)
    {
        return Installment::find($installmentId)->months;
    }

    public function getInstallmentMonthyAmount($installmentId)
    {
        return Installment::find($installmentId)->monthly_payment_amount;
    }

    public function generateUniqueEnrollmentId(): string
    {
        $latestEnrollmentId = $this->getMaxBaseEnrollmentId();
        $latestNumber       = (int) str_replace('ENR-', '', $latestEnrollmentId);

        return 'ENR-' . ($latestNumber + 1);
    }

    public function getMaxBaseEnrollmentId(): string
    {
        $maxBaseEnrollmentId = Enrollment::pluck('enrollmentID')
            ->map(fn($id) => (int) str_replace('ENR-', '', $id))
            ->max();

        return 'ENR-' . ($maxBaseEnrollmentId ?: 500);
    }

    public function getTransactions()
    {
        return $this->payment->transactions()->pluck('id');
    }

    public function createInstallments($months, $amount, $payableAmount, $firstTransactionAmount)
    {
        $baseDate          = Carbon::now();
        $lastTransactionID = $this->payment->transactions->last()->transactionID;

        for ($i = 0; $i < $months; $i++) {
            $dueDate = $baseDate->copy()->addMonths($i + 1);
            // Generate the new transaction ID
            $newTransactionID = Transaction::incrementTransactionID($lastTransactionID);

            $this->payment->transactions()->create([
                'amount'         => $amount,
                'transactionID'  => $newTransactionID,
                'payment_method' => null,
                'due_date'       => $dueDate,
                'status'         => 'new',
            ]);
            $lastTransactionID = $newTransactionID;
        }
    }

    public function twoPaymentMethods($data)
    {
        $transaction   = reset($data['payment']['Transaction']);
        $installmentId = $data['installment_id'];
        // $paymentMethod = $transaction['payment_method'];
        // $transaction_ss = $transaction['transaction_ss'];

        $first_payment_method_amount = $transaction['first_payment_method_amount'];
        $payment_method_1            = $transaction['payment_method_1'];
        $first_transaction_proof     = $transaction['first_transaction_proof'];

        $second_payment_method_amount = $transaction['second_payment_method_amount'];
        $payment_method_2             = $transaction['payment_method_2'];
        $second_transaction_proof     = $transaction['second_transaction_proof'];

        $paymentTotalAmount = $first_payment_method_amount + $second_payment_method_amount;

        DB::beginTransaction();

        $this->update([
            'enrollmentID' => $this->generateUniqueEnrollmentId(),
        ]);

        if ($this->total_payment_amount) {
            $payableAmount = $this->total_payment_amount;
        } else {
            $payableAmount = $this->getCoursePrice();
        }

        try {
            // Check if the enrollment is an installment plan
            if ($this->has_installment_plan === true) {
                $months = $this->getInstallmentMonth($installmentId);
                $amount = $this->getInstallmentMonthyAmount($installmentId);

                // Update payment type to 'installment'
                $this->payment->update([
                    'student_id'    => $this->student_id,
                    'payment_type'  => 'installment',
                    'payment_price' => $this->total_payment_amount,
                    'status'        => 'processing',
                    'paymentID'     => $this->payment->generateUniquePaymentId(),
                ]);

                // Ensure there is a first transaction
                $firstTransaction = $this->payment->transactions()->firstOrFail();
                // Update the first transaction's fees to the course down payment amount
                $firstTransaction->update([
                    'payment_method'    => $payment_method_1,
                    'amount'            => $first_payment_method_amount,
                    'status'            => 'processing',
                    'transactionID'     => $firstTransaction->generateUniqueTransactionId(),
                    'due_date'          => Carbon::now(),
                    'transaction_proof' => is_array($first_transaction_proof) ? implode(', ', $first_transaction_proof) : $first_transaction_proof,

                ]);

                $secondTransaction = $this->payment->transactions()->create([
                    'payment_method'    => $payment_method_2,
                    'amount'            => $second_payment_method_amount,
                    'status'            => 'processing',
                    'transactionID'     => $firstTransaction->generateNextSubTransactionId(),
                    'due_date'          => Carbon::now(),
                    'transaction_proof' => is_array($second_transaction_proof) ? implode(', ', $second_transaction_proof) : $second_transaction_proof,

                ]);

                // Create additional installment transactions
                $this->createInstallments($months, $amount, $payableAmount, $paymentTotalAmount);

            } else {
                // Ensure batch is not null before proceeding
                if (is_null($this->batch)) {
                    throw new \Exception('Batch is null. Cannot retrieve course price.');
                }

                // Update payment type to 'cash_down'
                $this->payment->update([
                    'student_id'    => $this->student_id,
                    'payment_type'  => 'cash_down',
                    'payment_price' => $payableAmount, 'status' => 'processing',
                    'paymentID'     => $this->payment->generateUniquePaymentId(),
                ]);

                // Ensure there is a first transaction
                $firstTransaction = $this->payment->transactions()->firstOrFail();

                // Update the first transaction's fees to the course price and set due_date to null
                $firstTransaction->update([
                    'payment_method'    => $payment_method_1,
                    'amount'            => $first_payment_method_amount,
                    'status'            => 'processing',
                    'transactionID'     => $firstTransaction->generateUniqueTransactionId(),
                    'due_date'          => Carbon::now(),
                    'transaction_proof' => is_array($first_transaction_proof) ? implode(', ', $first_transaction_proof) : $first_transaction_proof,

                ]);

                $secondTransaction = $this->payment->transactions()->create([

                    'payment_method'    => $payment_method_2,
                    'amount'            => $second_payment_method_amount,
                    'status'            => 'processing',
                    'transactionID'     => $firstTransaction->generateNextSubTransactionId(),
                    'due_date'          => Carbon::now(),
                    'transaction_proof' => is_array($second_transaction_proof) ? implode(', ', $second_transaction_proof) : $second_transaction_proof,

                ]);

                if ($firstTransaction->payment_method === PaymentMethod::Cash->value) {
                    $firstTransaction->update(['status' => 'approved']);
                }
            }
            $transactions = $this->getTransactions();

            $invoice = $this->invoices()->create([
                'transaction_ids' => $transactions,
            ]);
            $invoice->update([
                'invoiceID' => $invoice->generateUniqueInvoiceId(),
            ]);

            $this->update(['invoice_id' => $invoice->id]);
            $this->payment->update(['invoice_id' => $invoice->id]);

            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            // Handle the case where the first transaction is not found
            // Log the error or handle as needed
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle any other exception that might occur
            throw $e;
        }

    }

    public function getEnrollment($installment_id, $total_discount_amount)
    {

        DB::beginTransaction();

        $this->update([
            'enrollmentID' => $this->generateUniqueEnrollmentId(),
        ]);

        if ($this->total_payment_amount) {
            $payableAmount = $this->total_payment_amount;
        } else {
            $payableAmount = $this->getCoursePrice();
        }

        try {
            // Check if the enrollment is an installment plan
            if ($this->has_installment_plan === true) {
                $months = $this->getInstallmentMonth($installment_id);
                $amount = $payableAmount - $this->getInstallmentDownPayment($installment_id);

                // Update payment type to 'installment'
                $this->payment->update([
                    'student_id'    => $this->student_id,
                    'payment_type'  => 'installment',
                    'payment_price' => $this->total_payment_amount,
                    'status'        => 'processing',
                    'paymentID'     => $this->payment->generateUniquePaymentId(),
                ]);

                // Ensure there is a first transaction
                $firstTransaction = $this->payment->transactions()->firstOrFail();

                // Update the first transaction's fees to the course down payment amount
                $firstTransaction->update([
                    'amount'        => $this->getInstallmentDownPayment($installment_id),
                    'status'        => 'processing',
                    'transactionID' => $firstTransaction->generateUniqueTransactionId(),
                    'due_date'      => Carbon::now(),
                ]);

                // Create additional installment transactions
                $this->createInstallments($months, $amount, $payableAmount, $firstTransaction->amount);

            } else {
                // Ensure batch is not null before proceeding
                if (is_null($this->batch)) {
                    throw new \Exception('Batch is null. Cannot retrieve course price.');
                }

                // Update payment type to 'cash_down'
                $this->payment->update([
                    'student_id'    => $this->student_id,
                    'payment_type'  => 'cash_down',
                    'payment_price' => $payableAmount, 'status' => 'processing',
                    'paymentID'     => $this->payment->generateUniquePaymentId(),
                ]);

                // Ensure there is a first transaction
                $firstTransaction = $this->payment->transactions()->firstOrFail();

                // Update the first transaction's fees to the course price and set due_date to null
                $firstTransaction->update([
                    'amount'        => $payableAmount,
                    'transactionID' => $firstTransaction->generateUniqueTransactionId(),
                    'status'        => 'processing',
                    'due_date'      => null,
                ]);
                if ($firstTransaction->payment_method === PaymentMethod::Cash->value) {
                    $firstTransaction->update(['status' => 'approved']);
                }
            }

            $transactions = $this->getTransactions();

            $invoice = $this->invoices()->create([
                'transaction_ids' => $transactions,
            ]);
            $invoice->update([
                'invoiceID' => $invoice->generateUniqueInvoiceId(),
            ]);

            $this->update(['invoice_id' => $invoice->id]);
            $this->payment->update(['invoice_id' => $invoice->id]);

            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            // Handle the case where the first transaction is not found
            // Log the error or handle as needed
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle any other exception that might occur
            throw $e;
        }

    }
}