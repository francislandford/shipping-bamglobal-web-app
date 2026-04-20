<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statement_of_facts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ship_id')->constrained()->restrictOnDelete();
            $table->foreignId('port_id')->constrained()->restrictOnDelete();
            $table->foreignId('pier_id')->nullable()->constrained()->nullOnDelete();

            $table->string('cargo')->nullable();
            $table->date('report_date')->nullable();
            $table->time('report_time')->nullable();

            $table->decimal('quantity_to_be_loaded', 14, 2)->default(0);
            $table->decimal('actual_total_loaded', 14, 2)->default(0);
            $table->decimal('balance_to_load', 14, 2)->default(0);

            $table->string('uom')->default('WMT');

            $table->boolean('loaded_by_grabs')->default(false);
            $table->boolean('loaded_by_ship_loaders')->default(false);
            $table->text('loading_method_notes')->nullable();

            $table->decimal('total_hours_lost', 8, 2)->default(0);

            $table->decimal('fwd_draft', 8, 2)->nullable();
            $table->decimal('mid_draft', 8, 2)->nullable();
            $table->decimal('aft_draft', 8, 2)->nullable();

            $table->dateTime('loading_completed_at')->nullable();
            $table->dateTime('vessel_sailed_at')->nullable();

            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statement_of_facts');
    }
};
