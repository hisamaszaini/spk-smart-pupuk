<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'tabel_kriteria';
    protected $primaryKey = 'id_kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'jenis_kriteria',
    ];

    public function bobot()
    {
        return $this->hasOne(BobotKriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
