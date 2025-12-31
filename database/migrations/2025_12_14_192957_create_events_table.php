<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration 1: Tabel events
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 100);
            $table->text('description')->nullable();
            $table->date('start_date');   // tanggal mulai event
            $table->date('end_date');     // tanggal berakhir event
            $table->timestamps();
        });

        Schema::create('event_tenant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->boolean('active')->default(true); // status tenant di event
            $table->timestamps();

            $table->unique(['tenant_id', 'event_id']); // satu tenant per event tidak duplikat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tenant');
        Schema::dropIfExists('events');
    }
};