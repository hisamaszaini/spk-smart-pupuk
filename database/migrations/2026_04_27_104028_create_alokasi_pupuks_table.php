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
        Schema::create('tabel_alokasi_pupuk', function (Blueprint $table) {
            $table->id('id_alokasi');
            $table->foreignId('id_petani')->constrained('tabel_petani', 'id_petani')->onDelete('cascade');
            $table->foreignId('id_periode')->constrained('tabel_periode_pupuk', 'id_periode')->onDelete('cascade');
            $table->float('nilai_preferensi');
            $table->float('jumlah_pupuk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_alokasi_pupuk');
    }
};
