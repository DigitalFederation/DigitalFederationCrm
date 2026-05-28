<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_protected')->default(false)->after('guard_name');
            $table->enum('protection_level', ['system', 'admin', 'user'])->default('user')->after('is_protected');
            $table->text('description')->nullable()->after('protection_level');
            $table->string('category', 100)->nullable()->after('description');
            $table->char('created_by', 36)->nullable()->after('category');
            $table->char('updated_by', 36)->nullable()->after('created_by');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['category']);
            $table->index(['is_protected']);
            $table->index(['protection_level']);
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropIndex(['category']);
            $table->dropIndex(['is_protected']);
            $table->dropIndex(['protection_level']);
            $table->dropColumn([
                'is_protected',
                'protection_level',
                'description',
                'category',
                'created_by',
                'updated_by',
            ]);
        });
    }
};
