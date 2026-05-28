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
            $table->string('national_code', 45)->nullable()->after('individual_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropColumn('national_code');
        });
    }
};
