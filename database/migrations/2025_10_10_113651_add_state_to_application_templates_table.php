<?php

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
        Schema::table('application_templates', function (Blueprint $table) {
            $table->enum('state', ['draft', 'open', 'closed', 'archived'])
                ->default('draft')
                ->after('is_active');
            $table->index('state');
        });

        // Migrate existing templates: if is_active=1 and within submission dates, set to 'open'
        DB::table('application_templates')
            ->where('is_active', true)
            ->where('submission_start_date', '<=', now())
            ->where('submission_end_date', '>=', now())
            ->update(['state' => 'open']);

        // Migrate existing templates: if is_active=1 but past submission dates, set to 'closed'
        DB::table('application_templates')
            ->where('is_active', true)
            ->where('submission_end_date', '<', now())
            ->update(['state' => 'closed']);

        // Inactive templates remain in 'draft' state
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_templates', function (Blueprint $table) {
            $table->dropIndex(['state']);
            $table->dropColumn('state');
        });
    }
};
