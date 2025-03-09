<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Frontend\TransactionApiRequest;

class TransactionController extends Controller
{
    public function update(Transaction $transaction, TransactionApiRequest $request)
    {

        $validatedData = $request->validated();

        if ($request->hasFile('transaction_proof')) {
            $transactionPath = $request->file('transaction_proof')->store('images/transactions');

            $validatedData['transaction_proof'] = Storage::url($transactionPath);
        }

        $transaction->update([
            'transaction_proof' => $validatedData['transaction_proof'],
            'payment_method' => $validatedData['payment_method'],
            'status' => 'processing',
        ]);

        return 'transaction status is now processing';

    }
}