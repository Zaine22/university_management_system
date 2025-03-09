<?php

namespace App\Http\Controllers;

use App\Exports\ExamResultExport;
use App\Http\Controllers\Controller;
use App\Imports\ExamResultsImport;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends Controller
{
    public function index()
    {
        //
    }

    public function excelExport($examID)
    {
        return (new ExamResultExport($examID))->download('exam_results.xlsx');
    }

    public function import($examID)
    {
        Excel::import(new ExamResultsImport($examID), 'exam_results.xlsx');

        return redirect('/')->with('success', 'All good!');
    }
}