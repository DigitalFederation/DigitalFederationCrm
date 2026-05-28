<?php

namespace Database\Migrations;

use Domain\Entities\Models\Entity;
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
    public $tableName = 'entity_federation';

    /**
     * Run the migrations.
     *
     * @table entity_federation
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entity');
            $table->foreignId('federation_id')->constrained('federation');
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
