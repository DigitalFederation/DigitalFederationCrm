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
            $table->foreignUuid('instructor_id')->nullable()->after('federation_name')->constrained('individual');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropForeign('instructor_id');
            $table->dropColumn('instructor_id');
        });
    }
};
