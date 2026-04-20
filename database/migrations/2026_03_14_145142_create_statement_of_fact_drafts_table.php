<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_fact_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('statement_of_fact_id')->constrained()->cascadeOnDelete();
            $table->decimal('fwd_draft', 8, 2)->nullable();
            $table->decimal('mid_draft', 8, 2)->nullable();
            $table->decimal('aft_draft', 8, 2)->nullable();
            $table->dateTime('loading_completed_at')->nullable();
            $table->dateTime('vessel_sailed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_fact_drafts');
    }
};
