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
        Schema::create('evt_discipline_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained('evt_disciplines');
            $table->string('description')->nullable(); // A short description for the fee, e.g., "Early Bird", "Standard", "For Age ClassGroup 16-20" etc.
            $table->decimal('amount', 8, 2); // Assuming a reasonable precision for the amount.
            $table->date('effective_from')->nullable(); // Starting date from which this fee is applicable.
            $table->date('effective_to')->nullable(); // Ending date till which this fee is applicable.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evt_discipline_fees');
    }
};
