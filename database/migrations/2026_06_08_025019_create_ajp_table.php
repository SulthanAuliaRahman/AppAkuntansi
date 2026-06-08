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
        Schema::create('ajp', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            // Menjaga atribut jenis_transaksi tetap sama persis dengan contoh transaksi umum
            $table->enum('jenis_transaksi', ['UMUM', 'PENYESUAIAN'])->default('PENYESUAIAN');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajp');
    }
};