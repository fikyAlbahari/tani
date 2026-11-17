<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemupukans', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('nama_tanaman');
        });

        Schema::table('penyemprotans', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('nama_tanaman');
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('nama_tanaman');
        });
    }

    public function down(): void
    {
        Schema::table('pemupukans', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });

        Schema::table('penyemprotans', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->dropColumn('lokasi');
        });
    }
};
