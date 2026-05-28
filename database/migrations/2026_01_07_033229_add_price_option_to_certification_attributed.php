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
            $table->string('price_option')->default('digital')->after('notes');
            $table->decimal('price_paid', 10, 2)->nullable()->after('price_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certification_attributed', function (Blueprint $table) {
            $table->dropColumn(['price_option', 'price_paid']);
        });
    }
};
