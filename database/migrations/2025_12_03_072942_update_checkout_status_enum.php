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
        // Update the enum status column to include new values
        Schema::table('checkout', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('checkout', function (Blueprint $table) {
            $table->enum('status', ['baru', 'diproses', 'diantar', 'selesai'])->default('baru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checkout', function (Blueprint $table) {
            //
        });
    }
};
