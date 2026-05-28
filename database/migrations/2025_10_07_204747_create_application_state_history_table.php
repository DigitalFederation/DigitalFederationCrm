<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $tableName = 'application_state_history';

    /**
     * Run the migrations.
     *
     * @table application_state_history
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('event_applications')->onDelete('cascade');
            $table->string('from_state', 200)->nullable();
            $table->string('to_state', 200);
            $table->foreignUuid('changed_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['application_id'], 'idx_state_history_application');
            $table->index(['changed_by'], 'idx_state_history_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
