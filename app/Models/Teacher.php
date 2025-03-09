<?php
namespace App\Models;

use App\Traits\FormatNrc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use FormatNrc, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'teacherID',
        'salary',
        'user_id',
        'employee_id',
        'nrc',
        'dob',
        'join_date',
        'nationality',
        'phone',
        'mail',
        'marital_status',
        'education',
    ];

    public function familyMembers(): MorphMany
    {
        return $this->morphMany(FamilyMember::class, 'memberable');
    }

    public function nrcno(): BelongsTo
    {
        return $this->belongsTo(Nrc::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function generateUniqueTeacherId(): string
    {
        $latestTeacherId = $this->getMaxBaseTeacherId();
        $latestNumber    = (int) str_replace('RIT-', '', $latestTeacherId);

        return 'RIT-' . ($latestNumber + 1);
    }

    public function getMaxBaseTeacherId(): string
    {
        $maxBaseTeacherId = Teacher::pluck('teacherID')
            ->map(fn($id) => (int) str_replace('RIT-', '', $id))
            ->max();

        return 'RIT-' . ($maxBaseTeacherId ?: 499);
    }
}