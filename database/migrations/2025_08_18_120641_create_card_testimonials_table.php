<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_testimonials', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('card_id')->index();
            $t->string('author');
            $t->string('role')->nullable();
            $t->text('content')->nullable();
            $t->unsignedTinyInteger('rating')->nullable();
            $t->timestamps();
            $t->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('card_testimonials');
    }
};