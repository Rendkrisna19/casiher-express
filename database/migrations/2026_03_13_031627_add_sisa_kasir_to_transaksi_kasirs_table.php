<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_kasirs', function (Blueprint $table) {
            // Tambahkan kolom sisa_kasir setelah kolom id (atau sesuaikan urutannya)
            $table->integer('sisa_kasir')->default(0)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_kasirs', function (Blueprint $table) {
            $table->dropColumn('sisa_kasir');
        });
    }
};