<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->boolean('has_cmas_portal')->default(false)->after('public_description');
        });
    }

    public function down(): void
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->dropColumn('has_cmas_portal');
        });
    }
};
