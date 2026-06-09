<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles_akses_akun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('akuns_id')->constrained('akuns')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(
                ['role_id', 'akuns_id'],
                'cegah_duplikat_akses'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_akses_akun');
    }
};
