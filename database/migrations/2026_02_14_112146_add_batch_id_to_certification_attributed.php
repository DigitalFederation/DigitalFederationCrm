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
            $table->char('batch_id', 36)->nullable()->after('price_paid')->index();
        });
    }

    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropIndex(['batch_id']);
            $table->dropColumn('batch_id');
        });
    }
};
