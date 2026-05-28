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
        Schema::table('license_attributed', function (Blueprint $table) {
            $table->unsignedBigInteger('requested_by_id')->nullable()->after('requester_model_type');
            $table->enum('request_type', ['direct', 'entity_group'])->default('direct')->after('requested_by_id');
            $table->unsignedBigInteger('payment_id')->nullable()->after('request_type');
            $table->timestamp('purchased_at')->nullable()->after('payment_id');

            $table->foreign('requested_by_id')->references('id')->on('entity')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license_attributed', function (Blueprint $table) {
            $table->dropForeign(['requested_by_id']);
            $table->dropColumn(['requested_by_id', 'request_type', 'payment_id', 'purchased_at']);
        });
    }
};
