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
        Schema::create('tabel_petani', function (Blueprint $table) {
            $table->id('id_petani');
            $table->string('nama_petani');
            $table->float('luas_lahan');
            $table->float('produktivitas_lahan');
            $table->string('status_kepemilikan_lahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_petani');
    }
};
