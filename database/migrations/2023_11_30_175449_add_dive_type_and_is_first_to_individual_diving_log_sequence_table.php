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
            $table->string('dive_type')->after('diving_log_id');
            $table->boolean('is_first')->default(false)->after('dive_type');
            $table->index(['individual_id', 'diving_log_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_diving_log_sequence', function (Blueprint $table) {
            $table->dropColumn('dive_type');
            $table->dropColumn('is_first');
            $table->dropIndex(['individual_id', 'diving_log_id']);
        });
    }
};
