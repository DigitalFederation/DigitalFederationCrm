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
            $table->unsignedBigInteger('federation_id')->nullable()->change();
            $table->string('code_cmas_delegate_federation')->nullable()->change();
            $table->string('appointment_by_bod_number')->nullable()->change();
            $table->date('date_of_bod_appointment')->nullable()->change();
            $table->date('date_of_report_reception')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
