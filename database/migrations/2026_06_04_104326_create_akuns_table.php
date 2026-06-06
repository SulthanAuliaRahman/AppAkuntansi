<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            // Relasi ke jenis_akun
            $table->foreignId('jenis_akun_id')->constrained('jenis_akun')->onDelete('cascade');
            $table->string('kode_akun', 20)->unique();
            $table->string('nama_akun', 150);
            $table->enum('saldo_normal', ['DEBET', 'KREDIT']);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};
