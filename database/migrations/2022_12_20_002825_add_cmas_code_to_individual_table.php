<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 7 char == 78364164096 possibilitites
     *
     * @return void
     */
    public function up()
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->string('code_cmas', 7)->index(); // make sure its efficient
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individual', function (Blueprint $table) {
            $table->dropColumn('code_cmas');
        });
    }
};
