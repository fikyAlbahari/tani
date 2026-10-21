<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_tanams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->string('varietas_benih')->nullable();
            $table->decimal('luas_lahan', 10, 2)->nullable();
            $table->date('tanggal_tanam');
            $table->date('perkiraan_panen')->nullable();
            $table->string('status')->default('Direncanakan');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pemupukans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_tanaman');
            $table->string('jenis_pupuk');
            $table->string('dosis')->nullable();
            $table->string('metode')->nullable();
            $table->string('status')->default('Terjadwal');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('penyemprotans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_tanaman');
            $table->string('jenis_pestisida');
            $table->string('dosis')->nullable();
            $table->string('target_hama')->nullable();
            $table->string('status')->default('Terjadwal');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyemprotans');
        Schema::dropIfExists('pemupukans');
        Schema::dropIfExists('jadwal_tanams');
    }
};
