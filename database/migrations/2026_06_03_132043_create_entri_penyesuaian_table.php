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
            $table->string('kode_akun_debet', 20);
            $table->string('kode_akun_kredit', 20);
            $table->bigInteger('jumlah');
            $table->string('keterangan', 255);
            $table->timestamps();
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
