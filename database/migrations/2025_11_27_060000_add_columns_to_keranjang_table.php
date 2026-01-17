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
        Schema::table('keranjang', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('menu_id');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2);
            $table->decimal('total_harga', 10, 2);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('menu_id')->references('id_menu')->on('menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjang', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['menu_id']);
            $table->dropColumn(['user_id', 'menu_id', 'jumlah', 'harga', 'total_harga']);
        });
    }
};
