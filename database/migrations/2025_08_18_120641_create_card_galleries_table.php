<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_galleries', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('card_id')->index();
            $t->string('title')->nullable();
            $t->string('image_path');
            $t->json('meta')->nullable();
            $t->integer('sort_order')->default(0);
            $t->timestamps();
            $t->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('card_galleries');
    }
};