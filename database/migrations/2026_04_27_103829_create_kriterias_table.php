<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tabel_kriteria', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('nama_kriteria');
            $table->string('jenis_kriteria'); // benefit / cost
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_kriteria');
    }
};
