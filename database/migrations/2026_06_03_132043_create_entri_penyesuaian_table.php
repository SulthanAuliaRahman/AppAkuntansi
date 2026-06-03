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
        Schema::create('entri_penyesuaian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun_debet', 10);
            $table->string('kode_akun_kredit', 10);
            $table->bigInteger('jumlah');
            $table->string('keterangan', 255);
            $table->timestamps();

            $table->foreign('kode_akun_debet')->references('kode')->on('akun');
            $table->foreign('kode_akun_kredit')->references('kode')->on('akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entri_penyesuaian');
    }
};
