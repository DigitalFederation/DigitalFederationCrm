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
        Schema::table('evt_technical_delegates', function (Blueprint $table) {
            $table->date('date_bod_validation_report')->nullable();
            $table->string('num_bod_validation_report')->nullable();
        });
    }
};
