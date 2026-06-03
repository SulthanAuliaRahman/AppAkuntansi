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
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal', 10);
            $table->string('keterangan', 255);
            $table->string('akun_debet', 10);
            $table->string('akun_kredit', 10);
            $table->bigInteger('jumlah');
            $table->boolean('is_static')->default(false)->comment('true = data kasus studi, tidak bisa dihapus');
            $table->timestamps();

            $table->foreign('akun_debet')->references('kode')->on('akun');
            $table->foreign('akun_kredit')->references('kode')->on('akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal');
    }
};
