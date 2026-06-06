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
        Schema::create('detail_jurnal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jurnal_id');
            $table->string('akun_kode', 20);
            $table->enum('type', ['debet', 'kredit']);
            $table->bigInteger('jumlah');
            $table->timestamps();

            $table->foreign('jurnal_id')->references('id')->on('jurnal')->onDelete('cascade');
            $table->foreign('akun_kode')->references('kode_akun')->on('akuns')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jurnal');
    }
};
