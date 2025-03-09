<?php
namespace App\Models;

use App\Traits\FormatNrc;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Student extends Model
{
    use FormatNrc, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'register_no',
        'user_id',
        'slug',
        'dob',
        'avatar',
        'nrc',
        'nationality',
        'phone',
        'gender',
        'mail',
        'marital_status',
        'admission_date',
        'address',
        'past_education',
        'past_qualification',
        'current_job_position',
        'graduated',
        'approval_documents',
    ];

    protected $casts = [
        'graduated'          => 'boolean',
        'dob'                => 'date',
        'admission_date'     => 'date',
        'approval_documents' => 'array',
    ];

    public function familyMembers()
    {
        return $this->morphMany(FamilyMember::class, 'memberable');
    }

    public function nrcno()
    {
        return $this->belongsTo(Nrc::class);
    }

    public function rollcalls()
    {
        return $this->hasMany(Rollcall::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // for relationship manager
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function batches()
    {
        return $this->hasManyThrough(Batch::class, // The model to access through the relationship
            Enrollment::class,                         // The intermediate model
            'student_id',                              // Foreign key on the Enrollment table
            'id',                                      // Foreign key on the Batch table (assumed to be primary key of Batch table)
            'id',                                      // Local key on the Student table
            'batch_id');                               // Local key on the Enrollment table);
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Enrollment::class);
    }

    public function exams()
    {
        return $this->hasManyThrough(Exam::class, Enrollment::class, 'student_id', 'id', 'id', 'batch_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Payment::class);
    }

    public function timetables()
    {
        return $this->hasManyThrough(
            Timetable::class,
            Enrollment::class,
            'student_id',
            'batch_id',
            'id',
            'batch_id'
        );

    }

    public function generateUniqueStudentId(): string
    {
        $latestStudentId = $this->getMaxBaseStudentId();
        $latestNumber    = (int) str_replace('RI-', '', $latestStudentId);

        return 'RI-' . ($latestNumber + 1);
    }

    public function getMaxBaseStudentId(): string
    {
        $maxBaseStudentId = Student::pluck('register_no')
            ->map(fn($id) => (int) str_replace('RI-', '', $id))
            ->max();

        return 'RI-' . ($maxBaseStudentId ?: 499);
    }

    public static function createData($data, $record)
    {
        DB::transaction(function () use ($data, $record) {
            $nrcFormatted = self::staticFormatNrc($data['nrcs_n'], $data);
            self::updateRecordWithNrc($record, $nrcFormatted);
            self::associateUserWithRecord($record);
        });
    }

    protected static function updateRecordWithNrc($record, $nrcFormatted)
    {
        $record->update([
            'nrc'            => $nrcFormatted,
            'slug'           => Str::slug($record->name),
            'admission_date' => Carbon::now(),
        ]);
    }

    protected static function associateUserWithRecord($record)
    {
        $user = User::where('email', $record->student_mail)->first();
        if ($user) {
            $record->update(['user_id' => $user->id]);
        }
    }
}