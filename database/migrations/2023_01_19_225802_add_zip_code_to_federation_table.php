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
            $table->string('zip_code', 15)->nullable()->after('vat_number');
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
            $table->dropColumn('zip_code');
        });
    }
};
