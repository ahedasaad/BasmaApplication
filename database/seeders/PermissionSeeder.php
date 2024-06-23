<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            //Post Permissions
            'get_all_posts',
            'get_all_pending_posts',
            'create_post',
            'update_post',
            'delete_post',
            'filter_post',
            'accept_post',
            'unaccept_post',
            'get_user_posts',
            'add_like',
            'remove_like',

            //Product Management
            'get_all_categories',
            'get_all_products',
            'get_products_by_category',
            'get_approved_products',
            'create_product',
            'update_product',
            'delete_product',
            'filter_product',
            'accept_product',
            'unaccept_product',
            'get_user_products',
            'get_pending_products',
            'get_rejected_products',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminRole = Role::create(['name' => 'admin']);

        $donorRole = Role::create(['name' => 'donor']);

        $childRole = Role::create(['name' => 'child']);

        $representativeRole = Role::create(['name' => 'representative']);

        $employeeRole = Role::create(['name' => 'employee']);
    }
}
