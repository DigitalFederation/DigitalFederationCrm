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
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            $table->renameColumn('price', 'per_person_price');
            // $table->float('discipline_price')->nullable();
            $table->float('event_fee')->nullable();
            $table->float('total_price')->after('event_fee');
            $table->unsignedBigInteger('per_person_pricing_id')->nullable();
            $table->unsignedBigInteger('event_fee_pricing_id')->nullable();
            // $table->renameColumn('pricing_id', 'discipline_pricing_id');
            $table->dropColumn('price_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_athletes_enrollment', function (Blueprint $table) {
            $table->renameColumn('per_person_price', 'price');
            $table->string('price_type')->after('price');
            $table->dropColumn(['discipline_price', 'event_fee', 'total_price', 'per_person_pricing_id', 'event_fee_pricing_id']);
            $table->renameColumn('discipline_pricing_id', 'pricing_id');
        });
    }
};
