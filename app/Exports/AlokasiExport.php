<?php

namespace App\Exports;

use App\Models\AlokasiPupuk;
use App\Models\PeriodePupuk;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlokasiExport implements FromView, ShouldAutoSize
{
    protected $periode_id;

    public function __construct($periode_id = null)
    {
        $this->periode_id = $periode_id;
    }

    public function view(): View
    {
        $periode = $this->periode_id ? PeriodePupuk::find($this->periode_id) : null;
        $periodeName = $periode ? $periode->periode : 'Semua Periode';

        $alokasis = AlokasiPupuk::with(['petani', 'periode'])
            ->when($this->periode_id, function ($q) {
                $q->where('id_periode', $this->periode_id);
            })
            ->get();

        return view('exports.alokasi', [
            'alokasis' => $alokasis,
            'periodeName' => $periodeName
        ]);
    }
}
