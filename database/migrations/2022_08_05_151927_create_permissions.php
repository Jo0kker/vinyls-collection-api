<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'roles' => 'normal',
            'permissions' => 'normal',
            'users' => 'softDeletes',
            'password_resets' => 'normal',
            'failed_jobs' => 'normal',
            'personal_access_tokens' => 'normal',
            'collections' => 'normal',
            'vinyls' => 'normal',
            'searches' => 'normal',
            'trades' => 'normal'
        ];

        $permissionsNormal = [
            'view',
            'create',
            'update',
            'delete'
        ];

        $permissionsSoftDeletes = [
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'force_delete'
        ];

        foreach ($tables as $table => $type) {
            foreach (${'permissions'.\Illuminate\Support\Str::studly($type)} as $permission) {
                Permission::create(['name' => "{$permission} {$table}"]);
            }
        }

        $roles = [
            'Administrator' => $tables,
            'Moderator' => [
                'users' => $permissionsNormal,
            ]
        ];

        foreach ($roles as $role => $tables) {
            $roleModel = Role::create(['name' => $role]);

            foreach ($tables as $table => $permissions) {
                if (!is_array($permissions)) {
                    foreach (${'permissions'.\Illuminate\Support\Str::studly($permissions)} as $permission) {
                        $roleModel->givePermissionTo("{$permission} {$table}");
                    }
                } else {
                    foreach ($permissions as $permission) {
                        $roleModel->givePermissionTo("{$permission} {$table}");
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
