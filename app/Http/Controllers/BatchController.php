<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\BatchApiResource;


class BatchController extends Controller
{
    public function index()
    {
        return BatchApiResource::collection(
            Batch::all(),
        );
    }

    public function show($slug)
    {
        $book = Batch::where('batch_slug', $slug)->firstOrFail();

        return new BatchApiResource($book);
    }
}