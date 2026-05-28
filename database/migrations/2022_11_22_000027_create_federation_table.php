<?php

namespace Database\Migrations;

use Domain\Federations\Models\Federation;
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
    public $tableName = 'federation';

    /**
     * Run the migrations.
     *
     * @table federation
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('country');
            $table->foreignId('parent_id')->nullable()->constrained('federation');
            $table->string('name', 200);
            $table->tinyInteger('is_local')->nullable()->default(0);
            $table->string('legal_name', 200)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('location', 200)->nullable();
            $table->string('website', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->json('board_members')->nullable();
            $table->softDeletes();

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
