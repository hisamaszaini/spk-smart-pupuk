<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $table = 'tabel_petani';
    protected $primaryKey = 'id_petani';

    protected $fillable = [
        'nama_petani',
        'luas_lahan',
        'produktivitas_lahan',
        'status_kepemilikan_lahan',
    ];
}
