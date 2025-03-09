<?php
namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'transactionID',
        'amount',
        'payment_method',
        'status',
        'due_date',
        'transaction_proof',
    ];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
        'status'         => PaymentStatus::class,
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function generateUniqueTransactionId(): string
    {
        $latestTransactionId = $this->getMaxBaseTransactionId();
        $latestNumber        = (int) str_replace('TSC-', '', $latestTransactionId);

        return 'TSC-' . ($latestNumber + 1);
    }

    public function getMaxBaseTransactionId(): string
    {
        $maxBaseTransactionId = Transaction::pluck('transactionID')
            ->map(fn($id) => (int) str_replace('TSC-', '', $id))
            ->max();

        return 'TSC-' . ($maxBaseTransactionId ?: 499);
    }

    public static function incrementTransactionID($transactionID)
    {
        [$prefix, $number] = explode('-', $transactionID);

        $newNumber = (int) $number + 1;

        return $prefix . '-' . $newNumber;
    }

    public function getPaymentMethodLabel(): string
    {
        return $this->payment_method->label();
    }

    public function generateNextSubTransactionId()
    {
        $baseTransactionId = $this->transactionID;

        $latestSubTransaction = self::where('transactionID', 'LIKE', $baseTransactionId . '-%')
            ->orderBy('id', 'desc')
            ->value('transactionID');

        if ($latestSubTransaction) {
            $latestSuffix = (int) substr($latestSubTransaction, strrpos($latestSubTransaction, '-') + 1);
            $newSuffix    = $latestSuffix + 1;
        } else {
            $newSuffix = 1;
        }

        return $baseTransactionId . '-' . $newSuffix;
    }

}