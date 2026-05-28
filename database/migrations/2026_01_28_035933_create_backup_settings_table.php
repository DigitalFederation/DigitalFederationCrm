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
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        DB::table('backup_settings')->insert([
            [
                'key' => 'backup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable or disable scheduled backups',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'description' => 'Backup frequency: daily, twice_daily, every_six_hours, weekly',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'backup_time',
                'value' => '02:00',
                'type' => 'string',
                'description' => 'Time of day (HH:MM) to run backups',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'retention_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Number of days to keep daily backups',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_storage_mb',
                'value' => '5000',
                'type' => 'integer',
                'description' => 'Maximum total storage in MB before oldest backups are pruned',
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
        Schema::dropIfExists('backup_settings');
    }
};
