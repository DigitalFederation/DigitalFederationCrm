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
        Schema::create('evt_technical_delegates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('federation_id');
            $table->unsignedBigInteger('competition_id');

            $table->string('name');

            $table->string('code_cmas_delegate_federation');
            $table->string('appointment_by_bod_number');
            $table->date('date_of_bod_appointment');
            $table->date('date_of_report_reception');
            $table->text('remarks')->nullable();

            // Foreign key constraint
            $table->foreign('federation_id')->references('id')->on('federation');
            $table->foreign('competition_id')->references('id')->on('evt_competitions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_technical_delegates');
    }
};
