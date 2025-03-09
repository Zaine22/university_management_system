<?php
namespace App\Exports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubjectExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Subject::all();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Subject Name',
            'Subject Description',
            'Subject Thumbnail',
            'Chapters'
        ];
    }

    public function map($subject): array
    {
        $chapterNames = '';
        
        if ($subject->chapter_ids) {
            $chapterIds = json_decode($subject->chapter_ids, true);
            $chapters = \App\Models\Chapter::whereIn('id', $chapterIds)->pluck('chapter_name')->toArray();
            $chapterNames = implode(', ', $chapters);
        }

        return [
            $subject->id,
            $subject->subject_name,
            $subject->subject_description,
            $subject->subject_thumbnail,
            $chapterNames
        ];
    }
}