<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_fact_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_of_fact_id')->constrained()->cascadeOnDelete();
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->string('description');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_fact_events');
    }
};
