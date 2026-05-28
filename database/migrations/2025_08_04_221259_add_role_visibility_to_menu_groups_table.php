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
        Schema::table('menu_groups', function (Blueprint $table) {
            $table->enum('visibility_type', ['all', 'roles'])
                ->default('all')
                ->after('active')
                ->comment('Visibility type: all users or specific roles');

            $table->json('required_roles')
                ->nullable()
                ->after('visibility_type')
                ->comment('Array of role names that can see this group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_groups', function (Blueprint $table) {
            $table->dropColumn(['visibility_type', 'required_roles']);
        });
    }
};
