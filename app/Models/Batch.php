<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'batch',
        'code',
        'name',
        'slug',
        'thumbnail',
        'total_section_count',
        'description',
        'start_date',
        'duration',
        'subject_ids',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'subject_ids' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'id', 'subject_ids');
    }

    public function getSubjects()
    {
        $subject_ids = $this->subject_ids;

        return Subject::whereIn('id', $subject_ids)->get();
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class, 'batch_id', 'id', 'id', 'student_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}