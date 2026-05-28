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
    public $tableName = 'membership_plan';

    /**
     * Run the migrations.
     *
     * @table membership_plan
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_id')->nullable()->constrained('committee');
            $table->string('name', 45);
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('interval')->nullable();
            $table->enum('interval_unit', ['weeks', 'months', 'years'])->nullable();
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
