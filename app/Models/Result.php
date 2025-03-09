<?php
namespace App\Models;

use App\Exports\ExamResultExport;
use App\Imports\ExamResultsImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;

class Result extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exam_id',
        'student_id',
        'marks',
        'grade',
        'is_present',
    ];

    protected $casts = [
        'is_present' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public static function excelExport($examID)
    {
        return (new ExamResultExport($examID))->download('exam_results.xlsx');
    }

    public static function excelImport(int $examId, $file)
    {
        Excel::import(new ExamResultsImport($examId), $file);
    }
}