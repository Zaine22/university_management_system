<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CourseApiResource;


class CourseController extends Controller
{
    public function index()
    {
        return CourseApiResource::collection(
            Course::all(),
        );
    }

    public function show($slug)
    {
        $book = Course::where('course_slug', $slug)->firstOrFail();

        return new CourseApiResource($book);
    }
}