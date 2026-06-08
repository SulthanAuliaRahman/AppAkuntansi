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
        Schema::create('detail_ajp', function (Blueprint $table) {
            $table->id();
            
            // Relasi murni ke tabel induk ajp yang dibuat di atas
            $table->foreignId('ajp_id')->constrained('ajp')->onDelete('cascade');
            
            // Relasi murni ke tabel akuns (mengikuti pola detail_transaksi)
            $table->foreignId('akun_id')->constrained('akuns')->onDelete('cascade');
            
            // Atribut posisi (DEBET/KREDIT) dan nominal (18, 2) disamakan persis dengan contohmu
            $table->enum('posisi', ['DEBET', 'KREDIT']);
            $table->decimal('nominal', 18, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_ajp');
    }
};