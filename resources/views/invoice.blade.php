{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10px;
        }

        .container {
            padding: 0 10px;
            margin-top: 1.25rem;
        }

        .header {
            margin-top: 1.25rem;
            overflow: hidden;
        }

        .header img {
            width: 50%;
            /* max-width: 100px; Adjust this for proper size */
        }

        .header-left,
        .header-center,
        .header-right {
            display: inline-block;
            vertical-align: center;
        }

        .header-left {
            width: 25%;
        }

        .header-center {
            width: 50%;
            text-align: center;
        }

        .header-right {
            width: 25%;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
        }

        .header p {
            margin: 0;
        }

        .info-section {
            margin-top: 1rem;
            overflow: hidden;
        }

        .info-left{
            display: inline-block;
            width: 77%;
            vertical-align: top;
        }
        .info-right {
            display: inline-block;
            vertical-align: top;
            width: 20%;
        }

        .info-left p,
        .info-right p {
            margin: 0.5rem 0;
        }

        .table-container {
            margin-top: 1rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.75rem;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 0.5rem;
        }

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .total-row td {
            text-align: right;
            padding-right: 3rem;
        }

        .note {
            margin-top: 1rem;
            text-align: center;
        }

        .footer {
            margin-top: 1rem;
            overflow: hidden;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="header-left">
                <img src="{{ asset('/images/Example.png') }}" alt="">
            </div>
            <div class="header-center">
                <h1>Example</h1>
                <p>Office: No.5, Yuzana Street, Mingalar Taung Nyunt, Tamwe Township, Yangon.</p>
                <p>Phone: 09964051332, 09262620754</p>
            </div>
            <div class="header-right"></div>
        </div>

        <div class="info-section">
            <div class="info-left">
                <p>Student : <span>{{ $invoice->enrollment->student->student_name }}</span></p>
                <p>Contact Number : <span>{{ $invoice->enrollment->student->student_ph }}</span></p>
                <p>Course : <span>{{ $invoice->enrollment->batch->batch_name }}</span></p>
            </div>
            <div class="info-right">
                <p>Invoice Id : <span>{{ $invoice->invoiceID }}</span></p>
                <p>Payment Id : <span>{{ $invoice->enrollment->payment->paymentID }}</span></p>
                <p>Enrollment Id : <span>{{ $invoice->enrollment->enrollmentID }}</span></p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Description</th>
                        <th colspan="2">Payment Method</th>
                        <th rowspan="2">Amount</th>
                    </tr>
                    <tr>
                        <th>Cash Down</th>
                        <th>Installment</th>
                    </tr>
                </thead>

                <tbody>
                    @php $rowNumber = 1; @endphp
                    @foreach ($invoice->transactions as $transaction)
                        @if ($transaction->status == "paid")
                            <tr>
                                <td>{{ $rowNumber }}</td>
                                <td>School Fee . {{ $transaction->transactionID }}</td>

                                @if ($transaction->payment->payment_type == "cash_down")
                                    <td>yes</td>
                                @else
                                    <td>-</td>
                                @endif

                                @if ($transaction->payment->payment_type == "installment")
                                    <td>yes</td>
                                @else
                                    <td>-</td>
                                @endif

                                <td>{{ $transaction->amount }} MMK</td>
                            </tr>

                            @php $rowNumber++; @endphp
                        @endif
                    @endforeach

                    <tr class="total-row">
                        <td colspan="4">Total Amount</td>
                        <td>{{ $invoice->enrollment->payment->payment_price }} MMK</td>
                    </tr>

                    <tr class="total-row">
                        <td colspan="4">Remaining Amount</td>
                        <td>{{ $remainingAmount }} MMK</td>
                    </tr>

                    <tr class="total-row">
                        <td colspan="4">Paided Amount</td>
                        <td>{{ $paidAmount }} MMK</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="note">
            <p>** All fees are non-refundable and non-transferable **</p>
        </div>

        <div class="footer">
            <div class="footer-left">
                <p>Note</p>
            </div>
            <div class="footer-right">
                <p>date - {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>

</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >
        <meta
            http-equiv="X-UA-Compatible"
            content="ie=edge"
        >
        <title>Document</title>
        <style>
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 10px;
            }

            .container {
                padding: 0 10px;
                margin-top: 1.25rem;
            }

            .header-table {
                width: 100%;
                margin-top: 1.25rem;
                border: none !important;
            }

            .header-table td {
                vertical-align: center;
                text-align: center;
            }

            .header-table .logo {
                width: 25%;
                text-align: start
            }

            .header-table .center-content {
                width: 50%;
            }

            .header-table .empty {
                width: 25%;
            }

            .header h1 {
                font-size: 2.25rem;
                font-weight: bold;
                margin-bottom: 0.75rem;
            }

            .header p {
                margin: 0;
            }

            .info-section {
                display: table;
                width: 100%;
                margin-top: 1rem;
            }

            .info-left {
                display: table-cell;
                width: 77%;
                vertical-align: top;
            }

            .info-right {
                display: table-cell;
                width: 20%;
                vertical-align: top;
            }

            .info-left p,
            .info-right p {
                margin: 0.5rem 0;
            }

            .table-container {
                margin-top: 1rem;
                text-align: center;
            }

            .table_main {
                width: 100%;
                border-collapse: collapse;
                margin-top: 0.75rem;
            }

            th,
            .td {
                border: 1px solid #000;
                padding: 0.5rem;
            }

            th {
                text-align: center;
            }

            td {
                text-align: center;
            }

            .total-row td {
                text-align: right;
                padding-right: 3rem;
            }

            .note {
                margin-top: 1rem;
                text-align: center;
            }

            .footer-table {
                width: 100%;
                margin-top: 1rem;
                border-collapse: collapse;
            }

            .footer-table td {
                vertical-align: top;
                text-align: left;
            }

            .footer-table .right {
                text-align: right;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <!-- Header Section -->
            <table class="header-table">
                <tr>
                    <td class="logo">
                        <img
                            src="{{ asset("/images/Example.png") }}"
                            alt=""
                            style="max-width: 50%;"
                        >
                    </td>
                    <td class="center-content">
                        <h1>Example</h1>
                        <p>Office: No.5, Yuzana Street, Mingalar Taung Nyunt, Tamwe Township, Yangon.</p>
                        <p>Phone: 09964051332, 09262620754</p>
                    </td>
                    <td class="empty"></td>
                </tr>
            </table>

            <!-- Info Section -->
            <div class="info-section">
                <div class="info-left">
                    <p>Student: <span>{{ $invoice->enrollment->student->student_name }}</span></p>
                    <p>Contact Number: <span>{{ $invoice->enrollment->student->student_ph }}</span></p>
                    <p>Course: <span>{{ $invoice->enrollment->batch->batch_name }}</span></p>
                </div>
                <div class="info-right">
                    <p>Invoice Id: <span>{{ $invoice->invoiceID }}</span></p>
                    <p>Payment Id: <span>{{ $invoice->enrollment->payment->paymentID }}</span></p>
                    <p>Enrollment Id: <span>{{ $invoice->enrollment->enrollmentID }}</span></p>
                    <p>Payment Type: <span>{{ $invoice->enrollment->payment->payment_type }}</span></p>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-container">
                <table class="table_main">
                    <thead>
                        <tr>
                            <th rowspan="">No</th>
                            <th rowspan="">Description</th>
                            <th colspan="">Payment Method</th>
                            <th rowspan="">Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $rowNumber = 1; @endphp
                        @foreach ($invoice->transactions as $transaction)
                            @if ($transaction->status == "approved")
                                <tr>
                                    <td class="td">{{ $rowNumber }}</td>
                                    <td class="td">School Fee . {{ $transaction->transactionID }}</td>

                                    {{-- @if ($transaction->payment->payment_type == "cash_down")
                                    <td class="td">yes</td>
                                @else
                                    <td class="td">-</td>
                                @endif --}}

                                    <td>{{ $transaction->payment_method }}</td>

                                    {{-- @if ($transaction->payment->payment_type == "installment")
                                    <td class="td">yes</td>
                                @else
                                    <td class="td">-</td>
                                @endif --}}

                                    <td class="td">{{ $transaction->amount }} MMK</td>
                                </tr>

                                @php $rowNumber++; @endphp
                            @endif
                        @endforeach

                        <tr class="total-row">
                            <td
                                class="td"
                                colspan="3"
                            >Total Amount</td>
                            <td class="td">{{ $invoice->enrollment->payment->payment_price }} MMK</td>
                        </tr>

                        <tr class="total-row">
                            <td
                                class="td"
                                colspan="3"
                            >Remaining Amount</td>
                            <td class="td">{{ $remainingAmount }} MMK</td>
                        </tr>

                        <tr class="total-row">
                            <td
                                colspan="3"
                                class="td"
                            >Paided Amount</td>
                            <td class="td">{{ $paidAmount }} MMK</td>
                        </tr>

                        {{-- <tr class="total-row">
                        <td colspan="3" class="td">Payment Type</td>
                        <td class="td">{{ $transaction->payment->payment_type }}</td>
                    </tr> --}}
                    </tbody>
                </table>
            </div>

            <!-- Note Section -->
            <div class="note">
                <p>** All fees are non-refundable and non-transferable **</p>
            </div>

            <!-- Footer Section -->
            <table class="footer-table">
                <tr>
                    <td>Note</td>
                    <td class="right">date - {{ now()->format("Y-m-d H:i:s") }}</td>
                </tr>
            </table>
        </div>

    </body>

</html>
