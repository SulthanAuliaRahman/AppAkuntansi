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
        Schema::table('jurnal', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['akun_debet']);
            $table->dropForeign(['akun_kredit']);
            // Drop columns
            $table->dropColumn(['akun_debet', 'akun_kredit', 'jumlah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal', function (Blueprint $table) {
            $table->string('akun_debet', 10)->after('keterangan');
            $table->string('akun_kredit', 10)->after('akun_debet');
            $table->bigInteger('jumlah')->after('akun_kredit');
            
            $table->foreign('akun_debet')->references('kode')->on('akun');
            $table->foreign('akun_kredit')->references('kode')->on('akun');
        });
    }
};
