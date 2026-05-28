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
        Schema::create('member_number_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedBigInteger('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default counters
        DB::table('member_number_settings')->insert([
            [
                'key' => 'individual_counter',
                'value' => 1,
                'description' => 'Current counter for individual member numbers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'entity_counter',
                'value' => 1,
                'description' => 'Current counter for entity member numbers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_number_settings');
    }
};
