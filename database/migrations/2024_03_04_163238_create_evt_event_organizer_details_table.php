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
        Schema::create('evt_event_organizer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('bod_meeting_no')->nullable();
            $table->date('date_sending_contract')->nullable();
            $table->date('date_sending_invoice_loc')->nullable();
            $table->date('date_reception_payment_loc')->nullable();
            $table->date('date_reception_contract_signed')->nullable();
            $table->date('date_reception_specific_rules')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('evt_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_event_organizer_details');
    }
};
