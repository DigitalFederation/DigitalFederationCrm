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
        Schema::table('individual_professional_role', function (Blueprint $table) {
            $table->foreignId('professional_role_id')->after('individual_id')->constrained('professional_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_professional_role', function (Blueprint $table) {
            $table->dropForeign(['professional_role_id']);
        });
    }
};
