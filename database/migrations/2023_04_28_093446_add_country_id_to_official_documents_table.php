<?php

use App\Models\Country;
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
        Schema::table('official_documents', function (Blueprint $table) {
            $table->foreignId('country_id')->after('individual_id')->constrained('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_documents', function (Blueprint $table) {
            $table->dropForeignIdFor(Country::class);
        });
    }
};
