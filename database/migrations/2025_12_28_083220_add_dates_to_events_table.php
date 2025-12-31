<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'start_date')) {
                $table->dateTime('start_date')->nullable()->after('description');
            }

            if (!Schema::hasColumn('events', 'end_date')) {
                $table->dateTime('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('events', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};