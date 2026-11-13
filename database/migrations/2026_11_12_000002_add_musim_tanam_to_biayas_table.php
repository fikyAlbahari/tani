<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->string('musim_tanam')->nullable()->after('nama_tanaman');
            $table->index('musim_tanam');
        });
    }

    public function down(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->dropIndex(['musim_tanam']);
            $table->dropColumn('musim_tanam');
        });
    }
};
