<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_fact_delays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_of_fact_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->decimal('hours_lost', 8, 2)->default(0);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_fact_delays');
    }
};
