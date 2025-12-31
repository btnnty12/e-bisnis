<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statistics_daily', function (Blueprint $table) {
            $table->id();
            $table->date('stat_date');

            $table->foreignId('mood_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('total_interactions')->default(0);
            $table->integer('unique_users')->default(0);

            $table->timestamps();

            $table->unique(['stat_date', 'mood_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics_daily');
    }
};