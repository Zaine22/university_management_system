<?php
namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'batch_id',
        'subject_id',
        'chapter_id',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function createAttendance()
    {
        DB::transaction(function () {
            $date = new DateTime($this->starts_at);

            $students = Batch::find($this->batch_id)->students;

            $attendance = Attendance::create([
                'date'         => $date->format('Y-m-d'),
                'timetable_id' => $this->id,
                'batch_id'     => $this->batch_id,
                'subject_id'   => $this->subject_id,
                'submitted'    => false,
            ]);

            foreach ($students as $student) {
                $attendance->rollcalls()->create([
                    'date'       => $date->format('Y-m-d'),
                    'student_id' => $student->id,
                ]);

            }

        });

    }
}