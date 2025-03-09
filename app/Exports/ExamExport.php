<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class ExamExport implements FromQuery
{
    use Exportable;

    protected string $ID;

    public function __construct(string $ID)
    {
        $this->ID = $ID;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Exam::query()->where('examId', $this->ID);
    }
}
