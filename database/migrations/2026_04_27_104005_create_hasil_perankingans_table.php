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
        Schema::create('tabel_hasil_perankingan', function (Blueprint $table) {
            $table->id('id_hasil');
            $table->foreignId('id_petani')->constrained('tabel_petani', 'id_petani')->onDelete('cascade');
            $table->decimal('nilai_preferensi', 10, 4);
            $table->integer('peringkat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_hasil_perankingan');
    }
};
