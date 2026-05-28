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
    public $tableName = 'membership';

    /**
     * Run the migrations.
     *
     * @table membership
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('federation_id')->constrained('federation');
            $table->string('name');
            $table->text('status_class');
            $table->dateTime('activated_at')->nullable();
            $table->date('current_term_starts_at')->nullable();
            $table->date('current_term_ends_at')->nullable();
            $table->dateTime('last_billing_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
