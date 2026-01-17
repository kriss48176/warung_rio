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
        Schema::table('checkout', function (Blueprint $table) {
            $table->unsignedBigInteger('pesanan_id')->nullable();
            $table->foreign('pesanan_id')->references('id_pesanan')->on('pesanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkout', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropColumn('pesanan_id');
        });
    }
};
