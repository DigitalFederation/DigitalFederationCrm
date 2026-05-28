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
        Schema::table('individual', function (Blueprint $table) {
            // Add composite index for efficient duplicate detection
            $table->index(['name', 'surname', 'birthdate', 'country_id'], 'duplicate_check_index');

            // Add index on email for faster lookups
            if (! Schema::hasIndex('individual', 'individual_email_index')) {
                $table->index('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropIndex('duplicate_check_index');

            if (Schema::hasIndex('individual', 'individual_email_index')) {
                $table->dropIndex('individual_email_index');
            }
        });
    }
};
