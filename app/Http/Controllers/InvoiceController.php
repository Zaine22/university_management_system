<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public static function invoice()
    {
        $invoice         = Invoice::first();
        $remainingAmount = 100000;
        $paidAmount      = 169879;

        return view('invoice', compact('invoice', 'remainingAmount', 'paidAmount'));
    }

    public static function printInvoice(Invoice $invoice)
    {
        // $workerName = str($invoice->worker_name)->replace(' ', '')->headline();
        $invoiceDate = $invoice->date;
        $workerName  = ' ';
        $fileName    = 'invoice.pdf';
        $total       = 0;

        $remainingAmount = 0;
        foreach ($invoice->transactions as $transaction) {
            if ($transaction->status !== 'paid') {
                $remainingAmount += $transaction->amount;
            }
        }

        $paidAmount = 0;
        foreach ($invoice->transactions as $transaction) {
            if ($transaction->status == 'paid') {
                $paidAmount += $transaction->amount;
            }
        }

        $pdf = Pdf::loadView('invoice', compact('invoice', 'remainingAmount', 'paidAmount'));

        return response()->streamDownload(fn() => print($pdf->output()), $fileName);
    }
}
