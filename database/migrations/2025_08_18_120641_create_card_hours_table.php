<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('card_hours', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('card_id')->index();
            $t->unsignedTinyInteger('weekday'); // 0=Sun ... 6=Sat
            $t->time('open_time')->nullable();
            $t->time('close_time')->nullable();
            $t->boolean('is_closed')->default(false);
            $t->timestamps();
            $t->unique(['card_id','weekday']);
            $t->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('card_hours');
    }
};