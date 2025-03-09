<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_name' => $this->faker->unique()->name(),
            'register_no' => 'RI-'.rand(10000000, 99999999),
            'student_slug' => $this->faker->unique()->slug(),
            'student_dob' => Carbon::now()->format('d-m-Y'),
            'student_nrc' => '14/BaKaLa(N)'.rand(100000, 999999),
            'nationality' => 'Burma',
            'student_ph' => $this->faker->unique()->phoneNumber(),
            'student_gender' => 'male',
            'student_mail' => $this->faker->unique()->email(),
            'marital_status' => 'Single',
            'student_admission_date' => Carbon::now()->format('d-m-Y'),
            'student_address' => $this->faker->text(),
            'student_past_education' => $this->faker->text(),
            'student_past_qualification' => $this->faker->text(),
            'current_job_position' => $this->faker->text(),
            'graduated' => false,
        ];
    }
}
