<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'student_id',
        'invoice_id',
        'paymentID',
        'status',
        'payment_type',
        'payment_price',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function generateUniquePaymentId(): string
    {
        $latestPaymentId = $this->getMaxBasePaymentId();
        $latestNumber    = (int) str_replace('PAY-', '', $latestPaymentId);

        return 'PAY-' . ($latestNumber + 1);
    }

    public function getMaxBasePaymentId(): string
    {
        $maxBasePaymentId = Payment::pluck('paymentID')
            ->map(fn($id) => (int) str_replace('PAY-', '', $id))
            ->max();

        return 'PAY-' . ($maxBasePaymentId ?: 500);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}