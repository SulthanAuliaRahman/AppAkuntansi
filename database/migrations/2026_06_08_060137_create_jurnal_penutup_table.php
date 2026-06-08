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
        Schema::create('jurnal_penutup', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('langkah')->comment('1=Tutup Pendapatan, 2=Tutup Beban, 3=Tutup ILR ke Modal, 4=Tutup Prive');
            $table->string('kode_akun', 20);
            $table->string('nama_akun', 100);
            $table->enum('posisi', ['debet', 'kredit']);
            $table->bigInteger('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_penutup');
    }
};
