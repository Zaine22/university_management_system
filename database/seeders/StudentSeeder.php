<?php

namespace Database\Seeders;

use App\Modules\Student\Model\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Student::create([
            'student_name' => 'Kyaw Zin Thet',
            'register_no' => 'RI-1012120',
            'student_slug' => 'kyaw-zin-thet',
            'user_id' => 6,
            'student_dob' => Carbon::now()->format('d-m-Y'),
            'student_avatar' => 'images/student_avatars/01HYWH6M773TFPTDH4TDMRW95T.jpg',
            'student_nrc' => '1/KaMaNa(N)452140',
            'nationality' => 'Mon',
            'student_ph' => '09 185324345',
            'student_gender' => 'male',
            'student_mail' => 'kyawzinthett7@gmail.com',
            'marital_status' => 'Single',
            'student_admission_date' => Carbon::now()->format('d-m-Y'),
            'student_address' => 'Yangon',
            'student_past_education' => 'bla_bla',
            'student_past_qualification' => 'bla_bla',
            'current_job_position' => 'developer',
            'graduated' => true,
            'approval_document' => 'images/student_avatars/01HYWH6M7TBMT60ERKX37MVFVA.jpg',
        ])->familyMembers()->create([
            'name' => 'john gyi',
            'relationship' => 'dad',
            'ph_no' => '0951243657',
            'address' => 'yangon',
            'profession' => 'manager',
            'income' => '100 lakhs',
        ]);

        // Student::factory()->count(9)->create();

        // Student::create([
        //     'student_name' => 'Nomi',
        //     'register_no' => 'RI-1012130',
        //     'student_slug' => 'nomi',
        //     'student_dob' =>  Carbon::now()->format('d m Y'),
        //     'student_avatar' => 'images/student_avatars/01HYWH6M773TFPTDH4TDMRW95T.jpg',
        //     'student_nrc' => '1/KaMaNa(N)434140',
        //     'nationality' => 'Mon',
        //     'student_ph' => '09 1334532445',
        //     'student_gender' => 'male',
        //     'student_mail' => 'nomi12@gmail.com',
        //     'marital_status' => 'Single',
        //     'student_admission_date' => Carbon::now()->format('d m Y'),
        //     'student_address' => 'Yangon',
        //     'student_past_education' => 'bla_bla',
        //     'student_past_qualification' => 'bla_bla',
        //     'current_job_position' => 'developer',
        //     'graduated' => true,
        //     'approval_document' => 'images/student_avatars/01HYWH6M7TBMT60ERKX37MVFVA.jpg'
        // ])->familyMembers()->create([
        //     'name' => 'monika',
        //     'relationship' => 'dad',
        //     'ph_no' => '095124233657',
        //     'address' => 'yangon',
        //     'profession' => 'manager',
        //     'income' => '100 lakhs'
        // ]);Event::factory()->count(10)->create();
    }
}
