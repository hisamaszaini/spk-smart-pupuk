<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPerankingan extends Model
{
    protected $table = 'tabel_hasil_perankingan';
    protected $primaryKey = 'id_hasil';

    protected $fillable = [
        'id_petani',
        'nilai_preferensi',
        'peringkat',
    ];

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'id_petani', 'id_petani');
    }
}
