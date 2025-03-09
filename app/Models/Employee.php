<?php
namespace App\Models;

use App\Enums\Departments;
use App\Traits\FormatNrc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Employee extends Model
{
    use FormatNrc, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'employeeID',
        'user_id',
        'avatar',
        'department',
        'salary',
        'nrc',
        'dob',
        'nationality',
        'phone',
        'mail',
        'marital_status',
        'join_date',
        'education',
    ];

    protected $casts = [
        'department' => Departments::class,
    ];

    public function familyMembers(): MorphMany
    {
        return $this->morphMany(FamilyMember::class, 'memberable');
    }

    public function nrcno(): BelongsTo
    {
        return $this->belongsTo(Nrc::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public static function createTeacher($employee, $record)
    {
        $nrcFormatted = self::staticFormatNrc($employee['nrcs_n'], $employee);

        $user = User::where('email', $record->mail)->first();
        if ($user) {
            $record->update([
                'nrc'  => $nrcFormatted,
                'slug' => Str::slug($record->name),
                'user_id'       => $user->id]);

        } else {
            $user1 = User::create([
                'name'     => $record->name,
                'email'    => $record->mail,
                'password' => Hash::make(12345678)]);

            $record->update([
                'nrc'  => $nrcFormatted,
                'slug' => Str::slug($record->name),
                'user_id'       => $user1->id]);

        }

    }

    public function generateUniqueEmployeeId(): string
    {
        $latestEmployeeId = $this->getMaxBaseEmployeeId();
        $latestNumber     = (int) str_replace('RIE-', '', $latestEmployeeId);

        return 'EMP-' . ($latestNumber + 1);
    }

    public function getMaxBaseEmployeeId(): string
    {
        $maxBaseEmployeeId = Employee::pluck('employeeID')
            ->map(fn($id) => (int) str_replace('RIE-', '', $id))
            ->max();

        return 'RIE-' . ($maxBaseEmployeeId ?: 499);
    }
}