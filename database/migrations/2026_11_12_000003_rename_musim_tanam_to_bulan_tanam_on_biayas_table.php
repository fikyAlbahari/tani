<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->dropIndex(['musim_tanam']);
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->renameColumn('musim_tanam', 'bulan_tanam');
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->index('bulan_tanam');
        });

        // Data lama berupa nama musim; ubah ke format bulan tanam (YYYY-MM)
        // berdasarkan bulan pada tanggal biaya terdekat.
        DB::table('biayas')
            ->whereNotNull('bulan_tanam')
            ->get(['id', 'tanggal'])
            ->each(function ($row) {
                DB::table('biayas')->where('id', $row->id)->update([
                    'bulan_tanam' => Carbon::parse($row->tanggal)->format('Y-m'),
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->dropIndex(['bulan_tanam']);
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->renameColumn('bulan_tanam', 'musim_tanam');
        });

        Schema::table('biayas', function (Blueprint $table) {
            $table->index('musim_tanam');
        });
    }
};
