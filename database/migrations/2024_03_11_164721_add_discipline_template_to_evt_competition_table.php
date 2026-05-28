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
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->unsignedBigInteger('discipline_template_id')->nullable();
            $table->foreign('discipline_template_id')
                ->references('id')
                ->on('evt_discipline_templates')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_competitions', function (Blueprint $table) {
            $table->dropForeign(['discipline_template_id']);
            $table->dropColumn('discipline_template_id');
        });
    }
};
