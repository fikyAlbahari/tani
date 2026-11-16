<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_tanams', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('luas_lahan');
            $table->string('nama_penggarap')->nullable()->after('lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_tanams', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'nama_penggarap']);
        });
    }
};
