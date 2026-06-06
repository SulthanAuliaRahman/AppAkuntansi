<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->foreign('kode_akun')->references('kode_akun')->on('akuns')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('entri_penyesuaian', function (Blueprint $table) {
            $table->foreign('kode_akun_debet')->references('kode_akun')->on('akuns')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kode_akun_kredit')->references('kode_akun')->on('akuns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->dropForeign(['kode_akun']);
        });

        Schema::table('entri_penyesuaian', function (Blueprint $table) {
            $table->dropForeign(['kode_akun_debet', 'kode_akun_kredit']);
        });
    }
};
