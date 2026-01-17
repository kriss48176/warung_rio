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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('pelanggan_id');
            $table->string('kode_transaksi')->unique();
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris']);
            $table->decimal('total_tagihan', 15, 2);
            $table->decimal('jumlah_dibayar', 15, 2)->nullable();
            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal']);
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('pesanan_id')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
