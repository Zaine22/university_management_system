<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Timetable;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\TimetableApiResource;

class TimetableController extends Controller
{
    public function index()
    {
        return TimetableApiResource::collection(
            Timetable::all()
        );
    }

    public static function timetable(Timetable $timetable)
    {
        $month = Carbon::parse($timetable->starts_at)->month;
        $timetables = Timetable::whereMonth('starts_at', $month)
            ->where('batch_id', $timetable->batch->id)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->starts_at)->format('Y-m-d');
            })
            ->sortKeys();
        $batch = $timetable->batch->batch_name;
        $name = 'timetable_'.$batch.'.pdf';

        $t = Pdf::loadView('timetable', compact('timetables', 'batch'));

        return response()->streamDownload(fn () => print ($t->output()), $name);
    }

    public function show(Timetable $timetable)
    {
        return new TimetableApiResource($timetable);
    }
}