<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity', function (Blueprint $table) {
            $table->string('legal_name', 100);
            $table->string('legal_responsible_person', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->dropColumn('fullname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity', function (Blueprint $table) {
            //
        });
    }
};
