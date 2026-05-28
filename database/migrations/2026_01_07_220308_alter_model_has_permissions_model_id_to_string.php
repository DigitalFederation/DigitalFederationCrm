<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Change model_id column from bigint to string (char(36)) to support UUID primary keys.
     * This is required because the users table uses UUIDs.
     */
    public function up(): void
    {
        // Drop foreign key constraint first
        DB::statement('ALTER TABLE model_has_permissions DROP FOREIGN KEY model_has_permissions_permission_id_foreign');

        // Drop primary key (composite: permission_id, model_id, model_type)
        DB::statement('ALTER TABLE model_has_permissions DROP PRIMARY KEY');

        // Change model_id to string type for UUID support
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id CHAR(36) NOT NULL');

        // Recreate primary key
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');

        // Re-add foreign key constraint
        DB::statement('ALTER TABLE model_has_permissions ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE');

        // Also update model_has_roles table if it exists
        if (\Schema::hasTable('model_has_roles')) {
            DB::statement('ALTER TABLE model_has_roles DROP FOREIGN KEY model_has_roles_role_id_foreign');
            DB::statement('ALTER TABLE model_has_roles DROP PRIMARY KEY');
            DB::statement('ALTER TABLE model_has_roles MODIFY model_id CHAR(36) NOT NULL');
            DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');
            DB::statement('ALTER TABLE model_has_roles ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE model_has_permissions DROP FOREIGN KEY model_has_permissions_permission_id_foreign');
        DB::statement('ALTER TABLE model_has_permissions DROP PRIMARY KEY');
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE');

        if (\Schema::hasTable('model_has_roles')) {
            DB::statement('ALTER TABLE model_has_roles DROP FOREIGN KEY model_has_roles_role_id_foreign');
            DB::statement('ALTER TABLE model_has_roles DROP PRIMARY KEY');
            DB::statement('ALTER TABLE model_has_roles MODIFY model_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');
            DB::statement('ALTER TABLE model_has_roles ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE');
        }
    }
};
