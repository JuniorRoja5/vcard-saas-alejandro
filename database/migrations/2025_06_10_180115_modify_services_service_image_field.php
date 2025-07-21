<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->text('service_image')->change();
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_image', 255)->change();
        });
    }
};
