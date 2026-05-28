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
        Schema::table('evt_events', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->text('location')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('start_registration')->nullable();
            $table->dateTime('end_registration')->nullable();
            $table->dateTime('freeze_date')->nullable();
            $table->json('other_deadlines')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evt_events', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'location', 'address', 'start_date', 'end_date',
                'start_registration', 'end_registration', 'freeze_date', 'other_deadlines',
            ]);
        });
    }
};
