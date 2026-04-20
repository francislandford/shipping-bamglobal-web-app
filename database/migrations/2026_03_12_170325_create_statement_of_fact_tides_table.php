<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_fact_tides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_of_fact_id')->constrained()->cascadeOnDelete();
            $table->date('tide_date')->nullable();
            $table->time('first_high_water')->nullable();
            $table->time('second_high_water')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_fact_tides');
    }
};
