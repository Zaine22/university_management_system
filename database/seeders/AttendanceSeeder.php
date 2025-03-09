<?php

namespace Database\Seeders;

use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timetable = Timetable::create([
            'teacher_id' => 1,
            'batch_id' => 1,
            'subject_id' => 1,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now(),
        ]);
        $timetable->createAttendance();
    }
}
