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
        Schema::table('evt_referees_enrollment', function (Blueprint $table) {
            if (! Schema::hasColumn('evt_referees_enrollment', 'entity_id')) {
                $table->foreignId('entity_id')->nullable()->after('federation_id')->constrained('entity')->nullOnDelete();
            } else {
                $table->foreign('entity_id')->references('id')->on('entity')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_referees_enrollment', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
            $table->dropColumn('entity_id');
        });
    }
};
