<?php
namespace App\Models;

use App\Exports\ExamExport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'grading_rule_id',
        'batch_id',
        'subject_id',
        'chapter_ids',
        'examId',
        'submitted',
        'teacher_id',
        'start_date_time',
        'end_date_time',
    ];

    protected $casts = [
        'chapter_ids' => 'array',
        'submitted'   => 'boolean',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'id', 'chapter_ids');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function gradingRule()
    {
        return $this->belongsTo(GradingRule::class, 'grading_rule_id', 'id');
    }

    public function examForAllStuents()
    {
        foreach ($this->batch->students as $student) {
            Result::create([
                'exam_id'    => $this->id,
                'student_id' => $student->id,
                'marks'      => '',
            ]);
        }
    }

    public function submitted()
    {
        $this->update([
            'submitted'  => true,
            'teacher_id' => auth()->user()->id,
        ]);
    }

    public static function excelExport($examID)
    {
        return (new ExamExport($examID))->download('exam.xlsx');
    }
}