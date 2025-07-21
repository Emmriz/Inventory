<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('items');
    }

    public function down()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // Add back any columns you had before
            $table->timestamps();
        });
    }
};
