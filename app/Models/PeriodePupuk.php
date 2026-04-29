<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodePupuk extends Model
{
    protected $table = 'tabel_periode_pupuk';
    protected $primaryKey = 'id_periode';

    protected $fillable = [
        'total_pupuk',
        'periode',
    ];
}
