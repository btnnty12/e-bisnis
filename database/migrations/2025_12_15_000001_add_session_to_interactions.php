<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add session_id column to store anonymous interactions and make user_id nullable
        Schema::table('interactions', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('menu_id');
        });

        // Make user_id nullable (raw statement to avoid requiring doctrine/dbal)
        DB::statement('ALTER TABLE interactions MODIFY user_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropColumn('session_id');
        });

        // Revert user_id to not null (may fail if rows with null exist)
        DB::statement('ALTER TABLE interactions MODIFY user_id BIGINT UNSIGNED NOT NULL');
    }
};
