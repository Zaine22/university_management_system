<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getFileUrl($filename)
    {
        $filePath = 'public/'.$filename;

        if (Storage::exists($filePath)) {
            $url = Storage::url($filePath);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'File not found.'], 404);
    }
}
