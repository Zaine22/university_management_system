<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // Program
            [
                'course_name' => 'RI Designated Program',
                'course_slug' => 'ri-designated-program',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 5600000,
                'course_installable' => true,
                'course_installment_price' => 6700000,
                'course_down_payment' => 1900000,
                'months' => 12,
                'monthly_payment_amount' => 400000,
            ],
            [
                'course_name' => 'FE preparation Course',
                'course_slug' => 'fe-preparation-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 1800000,
                'course_installable' => true,
                'course_installment_price' => 2100000,
                'course_down_payment' => 850000,
                'months' => 5,
                'monthly_payment_amount' => 250000,
            ],
            [
                'course_name' => 'FE Intensive Course',
                'course_slug' => 'fe-intensive-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 220000,
            ],

            // Diploma Program
            [
                'course_name' => 'Diploma in Web Development',
                'course_slug' => 'diploma-in-web-development',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 600000,
            ],
            [
                'course_name' => 'Diploma in Information Technology',
                'course_slug' => 'diploma-in-information-technology',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 800000,
            ],
            [
                'course_name' => 'Diploma in Business IT',
                'course_slug' => 'diploma-in-it',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 600000,
            ],

            // Certificate Program
            [
                'course_name' => 'Java Programming',
                'course_slug' => 'java-programming',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 400000,
            ],
            [
                'course_name' => 'Python Programming',
                'course_slug' => 'python-programming',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 200000,
            ],
            [
                'course_name' => 'PHP Programming',
                'course_slug' => 'php-programming',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 400000,
            ],
            [
                'course_name' => 'UI/UX Course',
                'course_slug' => 'ui-ux-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 400000,
            ],
            [
                'course_name' => 'Business English',
                'course_slug' => 'business-english',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 200000,
            ],
            [
                'course_name' => 'Networking Course',
                'course_slug' => 'networking-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 200000,
            ],
            [
                'course_name' => 'Hardware Course',
                'course_slug' => 'hardware-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 200000,
            ],
            [
                'course_name' => 'Cloud Computing',
                'course_slug' => 'cloud-computing',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 200000,
            ],

            // Degree Program
            [
                'course_name' => 'Bachelor of Science (IT & Data Science) Top-up (Online)',
                'course_slug' => 'bachelor-of-science',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 2400000,
                'course_installable' => true,
                'course_installment_price' => 3500000,
                'course_down_payment' => 1400000,
                'months' => 6,
                'monthly_payment_amount' => 350000,
            ],
            [
                'course_name' => 'Master of Science (IT & Data Science) (Online)',
                'course_slug' => 'master-of-science',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 2900000,
                'course_installable' => true,
                'course_installment_price' => 4100000,
                'course_down_payment' => 1700000,
                'months' => 6,
                'monthly_payment_amount' => 400000,
            ],
            [
                'course_name' => 'Foundation 2 year course',
                'course_slug' => 'foundation-2-year-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 12600000,
                'course_installable' => true,
                'course_installment_price' => 16800000,
                'course_down_payment' => 4200000,
                'months' => 18,
                'monthly_payment_amount' => 700000,
            ],
            [
                'course_name' => 'Foundation 2.5 year course',
                'course_slug' => 'foundation-2-5-year-course',
                'course_thumbnail' => 'images/thumbnails/01HZGN2WSDTG95BKZVW59BYHJN.jpg',
                'course_description' => $this->getLoremIpsum(),
                'course_price' => 16500000,
                'course_installable' => true,
                'course_installment_price' => 21000000,
                'course_down_payment' => 4200000,
                'months' => 24,
                'monthly_payment_amount' => 700000,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }

    /**
     * Returns a placeholder text for course descriptions.
     */
    private function getLoremIpsum(): string
    {
        return 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis magni asperiores alias itaque! Dolorum eius consectetur molestiae ullam minima voluptatem, itaque, nemo ipsum dolore voluptate omnis ab beatae totam dignissimos voluptates unde commodi! Ab molestiae distinctio ex odit modi aliquam veritatis officiis reprehenderit dignissimos quam minima sequi quos, provident fuga.';
    }
}
