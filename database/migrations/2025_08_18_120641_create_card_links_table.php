<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_links', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('card_id')->index();
            $t->string('label');
            $t->string('url');
            $t->string('icon')->nullable();
            $t->string('type')->nullable(); // website, whatsapp, map, etc.
            $t->integer('sort_order')->default(0);
            $t->timestamps();
            $t->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('card_links');
    }
};