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
        Schema::create('tabel_bobot_kriteria', function (Blueprint $table) {
            $table->id('id_bobot');
            $table->foreignId('id_kriteria')->constrained('tabel_kriteria', 'id_kriteria')->onDelete('cascade');
            $table->float('nilai_bobot');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_bobot_kriteria');
    }
};
