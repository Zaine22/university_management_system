<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeIDs = Employee::all()->pluck('id')->toArray();

        Teacher::create([
            'teacher_name' => 'David',
            'teacher_slug' => 'david',
            'teacherID' => 'RIT-122121',
            'teacher_salary' => '5000000',
            'employee_id' => $employeeIDs[0],
            'teacher_nrc' => '12/ThaGaKa(N)124245',
            'teacher_dob' => Carbon::now()->format('d-m-Y'),
            'teacher_doj' => Carbon::now()->format('d-m-Y'),
            'nationality' => 'Shan',
            'user_id' => 3,
            'teacher_ph' => '09451411',
            'teacher_mail' => 'david32@gmail.com',
            'marital_status' => 'Married',
            'education' => 'master degree',
        ])->familyMembers()->create([
            'name' => 'myo min',
            'relationship' => 'mon',
            'ph_no' => '0951234657',
            'address' => 'mandalay',
            'profession' => 'shop',
            'income' => '10 lakhs',
        ]);

        Teacher::create([
            'teacher_name' => 'Moe Moe',
            'teacher_slug' => 'moe-moe',
            'teacherID' => 'RIT-134121',
            'teacher_salary' => '5000000',
            'employee_id' => $employeeIDs[1],
            'teacher_nrc' => '12/ThaGaKa(N)23423',
            'teacher_dob' => Carbon::now()->format('d-m-Y'),
            'teacher_doj' => Carbon::now()->format('d-m-Y'),
            'nationality' => 'Shan',
            'teacher_ph' => '094231411',
            'teacher_mail' => 'moe32@gmail.com',
            'marital_status' => 'Married',
            'education' => 'master degree',
        ])->familyMembers()->create([
            'name' => 'myoni',
            'relationship' => 'mon',
            'ph_no' => '0951234657',
            'address' => 'mandalay',
            'profession' => 'shop',
            'income' => '10 lakhs',
        ]);
    }
}
