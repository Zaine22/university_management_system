<?php
namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Batch::create([
            'course_id'          => 1,
            'batch'              => 1,
            'course_batch_code'  => 'RI-111',
            'batch_name'         => 'Ri Designated Batch 1',
            'batch_slug'         => 'ri-designated-course',
            'course_sections'    => 120,
            'course_description' => 'Course description',
            'course_start_date'  => Carbon::now(),
            'course_duration'    => 20,
            'subject_ids'        => ['1', '2', '3', '4'],
        ]);

        $enrollment1 = Enrollment::create([
            'student_id'                => 1,
            'batch_id'                  => 1,
            'has_installment_plan'      => false,
            'enrollment_payment_amount' => 5600000,
            'total_payment_amount'      => 5600000,
        ]);

        $gg = $enrollment1->payments()->create();
        $gg->transactions()->create([
            'payment_method' => 'cash',
        ]);
        $enrollment1->getEnrollment();

        // enrollment 2
        // $enrollment2 = Enrollment::create([
        //     'student_id' => 2,
        //     'batch_id' => 1,
        //     'has_installment_plan' => true,
        //     'enrollment_payment_amount' => 6700000,
        //     'total_payment_amount' => 6700000,
        // ]);

        // $gg = $enrollment2->payments()->create();
        // $gg->transactions()->create([
        //     'payment_method' => "Cash",
        // ]);
        // $enrollment2->getEnrollment();

    }
}
