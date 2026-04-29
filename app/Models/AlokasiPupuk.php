<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlokasiPupuk extends Model
{
    protected $table = 'tabel_alokasi_pupuk';
    protected $primaryKey = 'id_alokasi';

    protected $fillable = [
        'id_petani',
        'id_periode',
        'nilai_preferensi',
        'jumlah_pupuk',
    ];

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'id_petani', 'id_petani');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodePupuk::class, 'id_periode', 'id_periode');
    }
}
