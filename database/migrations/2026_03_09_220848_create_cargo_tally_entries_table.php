<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cargo_tally_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ship_id')->constrained()->restrictOnDelete();
            $table->string('voyage');
            $table->foreignId('agency_id')->constrained()->restrictOnDelete();
            $table->foreignId('port_id')->constrained()->restrictOnDelete();
            $table->foreignId('pier_id')->nullable()->constrained()->nullOnDelete();

            $table->string('hatch_no')->nullable();
            $table->string('compartment')->nullable();
            $table->date('load_date')->nullable();
            $table->string('destination')->nullable();

            $table->text('package_description')->nullable();
            $table->decimal('total_quantity', 14, 2)->default(0);

            $table->text('condition_remarks')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargo_tally_entries');
    }
};
