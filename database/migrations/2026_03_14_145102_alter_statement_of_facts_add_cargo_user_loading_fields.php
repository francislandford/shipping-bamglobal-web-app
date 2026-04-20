<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('statement_of_facts', function (Blueprint $table) {
            $table->foreignId('cargo_id')->nullable()->after('pier_id')->constrained('cargos')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();

            $table->decimal('loaded_by_grabs_qty', 14, 2)->default(0)->after('uom');
            $table->decimal('loaded_by_ship_loaders_qty', 14, 2)->default(0)->after('loaded_by_grabs_qty');
        });
    }

    public function down(): void
    {
        Schema::table('statement_of_facts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cargo_id');
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
