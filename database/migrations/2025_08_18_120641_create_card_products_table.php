<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_products', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('card_id')->index();
            $t->string('name');
            $t->text('description')->nullable();
            $t->decimal('price', 12, 2)->nullable();
            $t->string('currency', 8)->default('USD');
            $t->string('sku')->nullable();
            $t->string('image_path')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('card_products');
    }
};