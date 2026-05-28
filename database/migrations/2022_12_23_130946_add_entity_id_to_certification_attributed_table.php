<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->foreignId('entity_id')->nullable()->after('federation_id')->constrained('entity');
            $table->string('entity_name', 150)->nullable()->after('federation_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropColumn('entity_id');
            $table->dropColumn('entity_name');
        });
    }
};
