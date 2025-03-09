<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            NRCSeeder::class,
            // EventSeeder::class,
            // StudentSeeder::class,
            // EmployeeSeeder::class,
            // TeacherSeeder::class,
            // EnrollmentSeeder::class,
            // AttendanceSeeder::class,
            // SubjectSeeder::class,
            // ChapterSeeder::class,
        ]);
    }
}