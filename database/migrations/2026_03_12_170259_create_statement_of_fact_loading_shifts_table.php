<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_fact_loading_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_of_fact_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->decimal('quantity_loaded', 14, 2)->default(0);
            $table->string('uom')->default('WMT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_fact_loading_shifts');
    }
};
