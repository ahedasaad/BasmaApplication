<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

            //Posts Permissions
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
            'count_posts',

            //Products Management
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
            'count_products',

            //Purches Management
            'get_my_basket',
            'add_to_basket',
            'remove_from_basket',
            'make_order',
            'show_order',
            'get_pending_orders',
            'get_received_orders',
            'get_unreceived_orders',
            'get_done_orders',
            'accept_order',
            'update_order_state',
            'update_order_state_done',
            'update_order_state_unreceived',
            'get_user_orders',

            //Users Management
            'add_employee',
            'add_representative',
            'add_child',
            'update_user',
            'update_child',
            'delete_user',
            'delete_child',
            'show_user_info',
            'show_child_info',
            'get_all_children',
            'get_all_employees',
            'get_all_representative',
            'filter_children',
            'count_donors',
            'count_childs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $adminPermissiosns = [

            'add_employee',
            'add_representative',
            'add_child',
            'update_user',
            'update_child',
            'delete_user',
            'delete_child',
            'show_user_info',
            'show_child_info',
            'get_all_children',
            'get_all_employees',
            'get_all_representative',
            'filter_children',
            'count_donors',
            'count_childs',

            'get_approved_products',
            'get_all_categories',
            'filter_product',
            'update_product',
            'delete_product',
            'accept_product',
            'unaccept_product',
            'get_pending_products',
            'get_rejected_products',
            'count_products',

            'get_all_posts',
            'get_all_pending_posts',
            'update_post',
            'delete_post',
            'filter_post',
            'accept_post',
            'unaccept_post',
            'count_posts',
        ];

        $donorPermissiosns = [
            'get_my_basket',
            'add_to_basket',
            'remove_from_basket',
            'make_order',
            'show_order',
            'get_user_orders',

            'get_all_categories',
            'get_products_by_category',
            'get_approved_products',
            'create_product',
            'filter_product',
            'get_user_products',

            'get_all_posts',
            'filter_post',
            'add_like',
            'remove_like',
        ];

        $childPermissiosns = [
            'create_post',
            'get_user_posts',
            'get_all_posts',
            'filter_post',
            'add_like',
            'remove_like',

            'show_child_info',
        ];

        $representativePermissiosns = [
            'show_order',
            'get_pending_orders',
            'get_received_orders',
            'get_unreceived_orders',
            'get_done_orders',
            'accept_order',
            'update_order_state',
            'update_order_state_done',
            'update_order_state_unreceived',
        ];

        $adminRole = Role::create(['name' => 'admin']);

        foreach ($adminPermissiosns as $permission) {
            $adminRole->givePermissionTo($permission);
        }

        $donorRole = Role::create(['name' => 'donor']);

        foreach ($donorPermissiosns as $permission) {
            $donorRole->givePermissionTo($permission);
        }

        $childRole = Role::create(['name' => 'child']);

        foreach ($childPermissiosns as $permission) {
            $childRole->givePermissionTo($permission);
        }

        $representativeRole = Role::create(['name' => 'representative']);

        foreach ($representativePermissiosns as $permission) {
            $representativeRole->givePermissionTo($permission);
        }

        $employeeRole = Role::create(['name' => 'employee']);

        // $rolesWithPermissions = [
        //     'admin' => [
        //         'login',
        //         'logout',
        //         'change_password',
        //         'add_employee',
        //         'add_representative',
        //         'add_child',
        //         'update_user',
        //         'update_child',
        //         'delete_user',
        //         'delete_child',
        //         'show_user_info',
        //         'show_child_info',
        //         'get_all_children',
        //         'get_all_employees',
        //         'get_all_representative',
        //         'filter_children',
        //         'count_donors',
        //         'count_childs',
        //         'get_approved_products',
        //         'get_all_categories',
        //         'filter_product',
        //         'update_product',
        //         'delete_product',
        //         'accept_product',
        //         'unaccept_product',
        //         'get_pending_products',
        //         'get_rejected_products',
        //         'count_products',
        //         'get_all_posts',
        //         'get_all_pending_posts',
        //         'update_post',
        //         'delete_post',
        //         'filter_post',
        //         'accept_post',
        //         'unaccept_post',
        //         'count_posts',
        //     ],
        //     'donor' => [
        //         'register',
        //         'verify_code',
        //         'resend_code',
        //         'login',
        //         'logout',
        //         'get_my_basket',
        //         'add_to_basket',
        //         'remove_from_basket',
        //         'make_order',
        //         'show_order',
        //         'get_user_orders',
        //         'get_all_categories',
        //         'get_products_by_category',
        //         'get_approved_products',
        //         'create_product',
        //         'filter_product',
        //         'get_user_products',
        //         'get_all_posts',
        //         'filter_post',
        //         'add_like',
        //         'remove_like',
        //     ],
        //     'child' => [
        //         'login_child',
        //         'logout',
        //         'create_post',
        //         'get_user_posts',
        //         'get_all_posts',
        //         'filter_post',
        //         'add_like',
        //         'remove_like',
        //         'show_child_info',
        //     ],
        //     'representative' => [
        //         'login',
        //         'logout',
        //         'show_order',
        //         'get_pending_orders',
        //         'get_received_orders',
        //         'get_unreceived_orders',
        //         'get_done_orders',
        //         'accept_order',
        //         'update_order_state',
        //         'update_order_state_done',
        //         'update_order_state_unreceived',
        //     ],
        // ];

        // foreach ($rolesWithPermissions as $roleName => $permissions) {
        //     $role = Role::create(['name' => $roleName]);
        //     foreach ($permissions as $permission) {
        //         $role->givePermissionTo($permission);
        //     }
        // }

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'mobile_number' => '12345678',
            'account_type' => 'admin',
            'is_active' => true,
        ]);

        $adminUser->assignRole($adminRole);
    }
}
