<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vcard_products', function (Blueprint $table) {
            $table->dropColumn(['product_status', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('vcard_products', function (Blueprint $table) {
            // columnas originales por si haces rollback
            $table->string('product_status', 191)->nullable();
            $table->string('status', 191)->default('1');
        });
    }
};

