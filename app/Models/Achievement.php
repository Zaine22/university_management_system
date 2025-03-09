<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'batch_id',
        'generate_certificate',
        'certificate_template_id',
        'certificateID',
        'certificates',
    ];

    protected $casts = [
        'generate_certificate' => 'boolean',
        'certificates'         => 'array',
    ];

    protected $with = ['student'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function certificateTemplate()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function generateUniqueCertificateId(): string
    {
        $latestCertificateId = $this->getMaxBaseCertificateId();
        $latestNumber        = (int) str_replace('CEF-', '', $latestCertificateId);

        return 'CEF-' . ($latestNumber + 1);
    }

    public function getMaxBaseCertificateId(): string
    {
        $maxBaseCertificateId = Achievement::pluck('certificateID')
            ->map(fn($id) => (int) str_replace('CEF-', '', $id))
            ->max();

        return 'CEF-' . ($maxBaseCertificateId ?: 499);
    }
}