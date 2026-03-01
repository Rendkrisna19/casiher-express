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
    Schema::create('transaksi_kasirs', function (Blueprint $table) {
        $table->id();
        $table->decimal('hasil_penjualan', 15, 2)->default(0);
        $table->decimal('saldo_sebelum', 15, 2)->default(0);
        $table->decimal('saldo_masuk_rek', 15, 2)->default(0);
        $table->decimal('setor_tunai', 15, 2)->default(0);
        $table->decimal('kas_masuk', 15, 2)->default(0);
        $table->decimal('saldo_rek', 15, 2)->default(0);
        $table->json('pengeluaran_items')->nullable(); // Menyimpan daftar pengeluaran sebagai JSON
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_kasirs');
    }
};
