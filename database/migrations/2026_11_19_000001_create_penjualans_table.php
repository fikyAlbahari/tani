<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_tanaman');
            $table->string('lokasi')->nullable();
            $table->decimal('jumlah', 12, 2);
            $table->string('satuan', 20)->default('kg');
            $table->decimal('harga_satuan', 14, 2);
            $table->decimal('total', 16, 2);
            $table->string('pembeli')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
