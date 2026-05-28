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
        Schema::table('individual_diving_log_sequence', function (Blueprint $table) {
            $table->integer('initial_log_number')->nullable();
            // drop is_first column
            $table->dropColumn('is_first');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_diving_log_sequence', function (Blueprint $table) {
            $table->dropColumn('initial_log_number');
            // add is_first column
            $table->boolean('is_first')->default(false);
        });
    }
};
