<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chapters = ['Corporate and Legal Affairs', 'Business Strategy', 'System Strategy', 'Development Technology',
            'Project Management', 'Service Management', 'Basic Theory', 'Computer System', 'Technology Element ', 'Hardware', 'Information Processing System', 'Software', 'Database', 'Network', 'Security', 'Data Structure and Algorithm',
            'FE Textbook Vol.2', 'Corporate and Legal Affairs', 'Business Strategy', 'Information System Strategy', 'Development Technology',
            'Project Management', 'Service Management', 'System Audit and Internal Control'];

        foreach ($chapters as $chapter) {
            Chapter::create([
                'chapter_name' => $chapter,
            ]);
        }
    }
}
