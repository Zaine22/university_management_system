<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'course_id',
        'name',
        'installment_price',
        'installmentID',
        'down_payment',
        'months',
        'monthly_payment_amount',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}