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
    public $tableName = 'entity';

    /**
     * Run the migrations.
     *
     * @table entity
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('country');
            $table->string('vat_number', 20)->nullable();
            $table->string('name', 191);
            $table->string('fullname', 191)->nullable()->default(null);
            $table->string('phone', 20)->nullable()->default(null);
            $table->string('website', 191)->nullable()->default(null);
            $table->string('address', 191)->nullable()->default(null);
            $table->string('location', 191)->nullable()->default(null);

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
