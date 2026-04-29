<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    protected $table = 'tabel_bobot_kriteria';
    protected $primaryKey = 'id_bobot';

    protected $fillable = [
        'id_kriteria',
        'nilai_bobot',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
