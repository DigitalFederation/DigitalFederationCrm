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
        Schema::table('affiliations', function (Blueprint $table) {
            $table->string('requester_type')->nullable()->after('status_class');
            $table->char('requester_id', 36)->nullable()->after('requester_type');
            $table->enum('request_type', ['direct', 'entity_group'])->default('direct')->after('requester_id');

            $table->index(['requester_type', 'requester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliations', function (Blueprint $table) {
            $table->dropIndex(['requester_type', 'requester_id']);
            $table->dropColumn(['requester_type', 'requester_id', 'request_type']);
        });
    }
};
