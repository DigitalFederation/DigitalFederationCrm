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
        Schema::table('diving_log', function (Blueprint $table) {
            if (! Schema::hasColumn('diving_log', 'diving_location_id')) {
                $table->unsignedBigInteger('diving_location_id')->nullable()->after('buddy_id');
            }

            // Optional: Add foreign key constraint if you have a 'diving_locations' table (adjust table and column names if different)
            // and if the foreign key doesn't already exist.
            // Example (pseudo-code for checking FK existence depends on DB driver and exact naming):
            // if (!Schema::hasForeignKey('diving_log', 'diving_log_diving_location_id_foreign')) {
            //    $table->foreign('diving_location_id')->references('id')->on('diving_locations')->onDelete('set null');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_log', function (Blueprint $table) {
            // Only attempt to drop the column if it exists.
            // This makes the down migration more resilient, though typically it should only undo what up() did.
            if (Schema::hasColumn('diving_log', 'diving_location_id')) {
                // Optional: Drop foreign key first if you added it and know its name
                // $table->dropForeign(['diving_location_id']); // Be careful with exact foreign key name
                $table->dropColumn('diving_location_id');
            }
        });
    }
};
