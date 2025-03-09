<?php
namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rollcall extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'student_id',
        'status',
        'reason',
        'rollcallable_id',
        'rollcallable_type',
    ];

    protected $casts = [
        'status' => AttendanceStatus::class,
    ];

    public function rollcallable(): MorphTo
    {
        return $this->morphTo();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getTimetableAttribute()
    {
        if ($this->rollcallable_type === Attendance::class) {
            return $this->rollcallable->timetable;
        }

        return null;
    }
}