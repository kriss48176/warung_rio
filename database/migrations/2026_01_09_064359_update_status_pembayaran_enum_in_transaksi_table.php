<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update data yang tidak standar
        DB::table('transaksi')
            ->whereNotIn('status_pembayaran', ['pending', 'lunas', 'gagal'])
            ->update(['status_pembayaran' => 'pending']);

        // 2. CEK DATABASE: Hanya jalankan ALTER jika menggunakan MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE transaksi MODIFY COLUMN status_pembayaran ENUM('pending', 'menunggu_konfirmasi', 'lunas', 'gagal') NOT NULL");
        } 
        // Jika SQLite (saat testing), kita abaikan atau gunakan cara lain karena SQLite tidak kaku soal ENUM
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE transaksi RENAME TO transaksi_old');

            Schema::create('transaksi', function (Blueprint $table) {
                $table->id('id_transaksi');
                $table->unsignedBigInteger('pesanan_id');
                $table->unsignedBigInteger('pelanggan_id');
                $table->string('kode_transaksi')->unique();
                $table->enum('metode_pembayaran', ['cod', 'bank_transfer', 'credit_card', 'debit_card', 'e_wallet', 'qris']);
                $table->decimal('total_tagihan', 15, 2);
                $table->decimal('jumlah_dibayar', 15, 2)->nullable();
                $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal']);
                $table->string('bukti_pembayaran')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->foreign('pesanan_id')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
                $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('cascade');
            });

            DB::statement("INSERT INTO transaksi SELECT id_transaksi, pesanan_id, pelanggan_id, kode_transaksi, metode_pembayaran, total_tagihan, jumlah_dibayar, CASE WHEN status_pembayaran = 'menunggu_konfirmasi' THEN 'pending' ELSE status_pembayaran END, bukti_pembayaran, catatan, created_at, updated_at FROM transaksi_old");

            DB::statement('DROP TABLE transaksi_old');
        }
    }
};