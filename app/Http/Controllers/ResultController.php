<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ResultApiResource;


class ResultController extends Controller
{
    public function index()
    {
        return ResultApiResource::collection(
            Result::all()
        );
    }
}