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
        Schema::table('diving_log_diving', function (Blueprint $table) {
            $table->string('average_depth_unit', 20)->nullable()->after('average_depth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_log_diving', function (Blueprint $table) {
            $table->dropColumn('average_depth_unit');
        });
    }
};
