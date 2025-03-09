<?php
namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoiceID',
        'enrollment_id',
        'date',
        'transaction_ids',
    ];

    protected $casts = ['transaction_ids' => 'array'];

 
    public function generateUniqueInvoiceId(): string
    {
        $latestInvoiceId = $this->getMaxBaseInvoiceId();
        $latestNumber    = (int) str_replace('INV-', '', $latestInvoiceId);

        return 'INV-' . ($latestNumber + 1);
    }

    public function getMaxBaseInvoiceId(): string
    {
        $maxBaseInvoiceId = Invoice::pluck('invoiceID')
            ->map(fn($id) => (int) str_replace('INV-', '', $id))
            ->max();

        return 'INV-' . ($maxBaseInvoiceId ?: 500);
    }

    public function invoiceIdExists($id)
    {
        return Invoice::where('invoiceID', 'INV-' . $id)->exists();
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Payment::class);
    }

    public function printInvoice()
    {
        $invoiceDate = $this->date;
        // $workerName = str($this->worker_name)->replace(' ', '')->headline();
        $workerName = ' ';
        $fileName   = "invoice_{$invoiceDate}_{$workerName}.pdf";
        $total      = 0;
        $pdf        = Pdf::loadView('print', compact('invoice', 'fileName', 'total'));

        return response()->streamDownload(fn() => print($pdf->output()), $fileName);
    }
}