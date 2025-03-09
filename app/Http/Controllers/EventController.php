<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\EventApiResource;


class EventController extends Controller
{
    public function index()
    {
        return EventApiResource::collection(
            Event::all()
        );
    }

    public function show($slug)
    {
        $event = Event::where('event_slug', $slug)->firstOrFail();

        return new EventApiResource($event);
    }
}