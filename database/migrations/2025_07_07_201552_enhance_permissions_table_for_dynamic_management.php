<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('guard_name');
            $table->text('description')->nullable()->after('category');
            $table->char('created_by', 36)->nullable()->after('description');
            $table->char('updated_by', 36)->nullable()->after('created_by');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['category']);
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropIndex(['category']);
            $table->dropColumn([
                'category',
                'description',
                'created_by',
                'updated_by',
            ]);
        });
    }
};
