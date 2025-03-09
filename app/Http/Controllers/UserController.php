<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function hello(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Validation for Excel file
        ]);

        if ($request->hasFile('file')) {
            // Import data
            Excel::import(new UsersImport, $request->file('file'));

            return redirect('/')->with('success', 'All good!');

        }

        return redirect()->with(['error' => 'File upload failed']);

    }
}
