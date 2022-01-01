<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Seeder010Roles extends Seeder
{
    public function run()
    {
        $system_slugs = [
            'setting_general',
            'setting_contact',
            'setting_developer',
            'setting_advance',
            'api',
            'log',
            'backup',
            'seo',
            'media',
            'report',
        ];
        $modelNameSlugs = config('cms.policies');
        $roles = [];
        $permissions = [];
        foreach($modelNameSlugs as $modelNameSlug)
        {
            $model_permissions = [];
            $model_permissions[] = Permission::updateOrCreate(['name' => $modelNameSlug . '_index']);
            $model_permissions[] = Permission::updateOrCreate(['name' => $modelNameSlug . '_view']);
            $model_permissions[] = Permission::updateOrCreate(['name' => $modelNameSlug . '_create']);
            $model_permissions[] = Permission::updateOrCreate(['name' => $modelNameSlug . '_update']);
            $model_permissions[] = Permission::updateOrCreate(['name' => $modelNameSlug . '_delete']);

            $model_role = Role::updateOrCreate(['name' => $modelNameSlug . '_manager']);
            $model_role->syncPermissions($model_permissions);
            $roles[] = $model_role->name;

            $permissions = array_merge($permissions, $model_permissions);
        }
        foreach($system_slugs as $system_slug)
        {
            $system_permission = Permission::updateOrCreate(['name' => $system_slug . '_manager']);
            $system_role = Role::updateOrCreate(['name' => $system_slug . '_manager']);
            $system_role->syncPermissions([$system_permission]);
            $roles[] = $system_role->name;

            $permissions[] = $system_permission;
        }

        $role_manager = Role::updateOrCreate(['name' => 'manager']);
        $role_manager->syncPermissions($permissions);

        $admin_users = User::getAdminUsers();
        foreach($admin_users as $admin_user){
            $admin_user->syncRoles($roles);
        }
    }
}
