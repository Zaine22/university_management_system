<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'timetable_id',
        'batch_id',
        'subject_id',
        'submitted',
        'teacher_id',
        'present_students',
        'absent_students',
        'leave_students',
    ];

    protected $casts = [
        'present_students' => 'array',
        'absent_students'  => 'array',
        'leave_students'   => 'array',
        'submitted'        => 'boolean',
    ];

    public function rollcalls(): MorphMany
    {
        return $this->morphMany(Rollcall::class, 'rollcallable');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    protected function submitted()
    {
        $present = $this->rollcalls()->where('status', 'present')->pluck('student_id')->toArray();
        $absent  = $this->rollcalls()->where('status', 'absent')->pluck('student_id')->toArray();
        $leave   = $this->rollcalls()->where('status', 'leave')->pluck('student_id')->toArray();

        $this->update(['submitted' => true,
            'teacher_id'               => $this->timetable->teacher_id,
            'present_students'         => $present,
            'absent_students'          => $absent,
            'leave_students'           => $leave]);
    }
}