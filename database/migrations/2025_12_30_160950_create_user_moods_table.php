<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_moods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // user yang mood-nya tercatat
            $table->unsignedBigInteger('mood_id');  // relasi ke moods.id
            $table->timestamps(); // created_at untuk tanggal mood tercatat
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_moods');
    }
};