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
        Schema::table('diving_technical_director_invitations', function (Blueprint $table) {
            if (! Schema::hasColumn('diving_technical_director_invitations', 'message')) {
                $table->text('message')->nullable()->after('certification_systems');
            }
            if (! Schema::hasColumn('diving_technical_director_invitations', 'license_id')) {
                $table->foreignId('license_id')->nullable()->after('license_attributed_id')->constrained('license')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diving_technical_director_invitations', function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropForeign(['license_id']);
            $table->dropColumn('license_id');
        });
    }
};
