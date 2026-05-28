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
        Schema::table('evt_attributes', function (Blueprint $table) {
            $table->boolean('required')->default(false)->after('fillable_global')->comment('Whether this attribute is required to be filled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_attributes', function (Blueprint $table) {
            $table->dropColumn('required');
        });
    }
};
