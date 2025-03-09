<?php

namespace App\Traits;

use App\Models\Nrc;

trait FormatNrc
{
    /**
     * Format the NRC number.
     *
     * @throws \Exception
     */
    public function formatNrc(array $nrcData): string
    {
        $nrc = Nrc::find($nrcData['nrcs_id']);
        if (! $nrc) {
            throw new \Exception('NRC not found');
        }

        $nrcName = $nrc->name_en;
        $type = $nrcData['type'];
        $number = $nrcData['nrc_num'];

        return "{$nrc->id}/{$nrcName}({$type}){$number}";
    }

    public static function staticFormatNrc($nrc_n, $data): string
    {

        $nrc = Nrc::where('id', $nrc_n)->firstOrFail();
        // dd($nrc);
        if (! $nrc) {
            throw new \Exception('NRC not found');
        }

        $nrcName = $nrc->name_en;
        $type = $data['type'];
        $number = $data['nrc_num'];

        return "{$nrc->nrc_code}/{$nrcName}({$type}){$number}";
    }
}
