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
        Schema::table('federation', function (Blueprint $table) {
            $table->string('code_cmas', 100)->nullable()->after('board_members');
            $table->string('vat_number', 20)->nullable()->after('code_cmas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('federation', function (Blueprint $table) {
            $table->dropColumn('code_cmas');
            $table->dropColumn('vat_number');
        });
    }
};
