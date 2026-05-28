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
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            $table->string('price_type')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->bigInteger('pricing_id')->nullable();
            $table->string('status_class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_officials_enrollment', function (Blueprint $table) {
            //
        });
    }
};
