<?php

namespace Database\Seeders;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'employee_name' => 'Kyaw Zin Thet',
            'employee_slug' => 'kyaw-zin-thet',
            'employeeID' => 'RIE-234234',
            'employee_avatar' => 'images/employee_avatars/01HYWNCEEXMJKYK1RRNYSQ7D1S.jpg',
            'employee_salary' => '200000',
            'employee_nrc' => '1/BaMaNa(P)834234',
            'employee_dob' => Carbon::now()->format('d-m-Y'),
            'nationality' => 'Chin',
            'employee_ph' => '01896656',
            'employee_mail' => 'kyawzin@gmail.com',
            'marital_status' => 'Married',
            'date_of_joining' => Carbon::now()->format('d-m-Y'),
            'education' => 'Lorem',
        ])->familyMembers()->create([
            'name' => 'kyaw',
            'relationship' => 'dad',
            'ph_no' => '0951234657',
            'address' => 'yangon',
            'profession' => 'manager',
            'income' => '100 lakhs',
        ]);

        Employee::create([
            'employee_name' => 'Thet Paing Lin',
            'employee_slug' => 'thet-paing-lin',
            'employeeID' => 'RIE-221834',
            'employee_avatar' => 'images/employee_avatars/01HYWNCEEXMJKYK1RRNYSQ7D1S.jpg',
            'employee_salary' => '200000',
            'employee_nrc' => '1/BaMaNa(P)845234',
            'employee_dob' => Carbon::now()->format('d-m-Y'),
            'nationality' => 'Chin',
            'employee_ph' => '01896646',
            'employee_mail' => 'thetpaing@gmail.com',
            'marital_status' => 'Married',
            'date_of_joining' => Carbon::now()->format('d-m-Y'),
            'education' => 'Lorem',
        ])->familyMembers()->create([
            'name' => 'moe',
            'relationship' => 'dad',
            'ph_no' => '09512234657',
            'address' => 'yangon',
            'profession' => 'manager',
            'income' => '100 lakhs',
        ]);
    }
}
