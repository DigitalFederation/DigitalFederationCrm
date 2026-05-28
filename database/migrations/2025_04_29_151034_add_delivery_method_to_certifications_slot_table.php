<?php

use App\Enums\DeliveryMethodEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->string('delivery_method')->nullable()->after('shipped_date');
        });

        DB::table('certifications_slot')
            ->whereNotNull('shipped_date')
            ->update(['delivery_method' => DeliveryMethodEnum::Shipped->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications_slot', function (Blueprint $table) {
            $table->dropColumn('delivery_method');
        });
    }
};
