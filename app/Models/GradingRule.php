<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingRule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'total_marks',
        'grade_rules',
    ];

    protected $casts = ['grade_rules' => 'array'];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}