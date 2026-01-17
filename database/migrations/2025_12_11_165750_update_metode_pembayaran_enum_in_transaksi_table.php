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
        // 1. Update data existing (tetap dijalankan di kedua DB)
        DB::table('transaksi')
            ->where('metode_pembayaran', 'tunai')
            ->update(['metode_pembayaran' => 'cod']);

        DB::table('transaksi')
            ->where('metode_pembayaran', 'transfer')
            ->update(['metode_pembayaran' => 'bank_transfer']);

        // 2. PROTEKSI DATABASE: Hanya jalankan MODIFY jika menggunakan MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE transaksi MODIFY COLUMN metode_pembayaran ENUM('cod', 'bank_transfer', 'credit_card', 'debit_card', 'e_wallet', 'qris') NOT NULL");
        } else {
            // Jika SQLite (Testing), gunakan Schema Builder yang aman
            Schema::table('transaksi', function (Blueprint $table) {
                $table->string('metode_pembayaran')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kita beri proteksi juga di bagian Down agar test tidak error saat rollback
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE transaksi RENAME TO transaksi_old');

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

            DB::statement("INSERT INTO transaksi SELECT id_transaksi, pesanan_id, pelanggan_id, kode_transaksi, CASE WHEN metode_pembayaran = 'cod' THEN 'tunai' WHEN metode_pembayaran = 'bank_transfer' THEN 'transfer' ELSE metode_pembayaran END, total_tagihan, jumlah_dibayar, status_pembayaran, bukti_pembayaran, catatan, created_at, updated_at FROM transaksi_old");

            DB::statement('DROP TABLE transaksi_old');
        }
    }
};