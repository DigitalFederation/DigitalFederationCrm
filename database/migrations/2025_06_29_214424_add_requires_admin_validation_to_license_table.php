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
        Schema::table('license', function (Blueprint $table) {
            $table->boolean('requires_admin_validation')->default(false);
        });

        // Update diving entity licenses to require admin validation
        DB::table('license')
            ->whereIn('committee_id', function ($query) {
                $query->select('id')
                    ->from('committee')
                    ->where('code', 'DIVING');
            })
            ->where('type_id', function ($query) {
                $query->select('id')
                    ->from('license_type')
                    ->where('name', 'entity');
            })
            ->update(['requires_admin_validation' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('license', function (Blueprint $table) {
            $table->dropColumn('requires_admin_validation');
        });
    }
};
