<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        // Assigning admin permission
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        // Assgining user permission
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return
                substr($permission->title, 0, 5)        !=  'user_' 
                && substr($permission->title, 0, 5)     !=  'role_' 
                && substr($permission->title, 0, 11)    !=  'permission_'  
                && substr($permission->title, 0, 9)     !=  'settings_' 
                && substr($permission->title, 0, 9)     !=  'patients_' 
                && substr($permission->title, 0, 8)     !=  'doctors_' 
                && substr($permission->title, 0, 10)    !=  'employees_' 
                && substr($permission->title, 0, 22)    !=  'diagnostic_categories_'
                && substr($permission->title, 0, 10)    !=  'pathology_'
                && substr($permission->title, 0, 22)    !=  'investigation_reports_'
                && substr($permission->title, 0, 28)    !=  'diagnostic_report_templates_'
                && substr($permission->title, 0, 17)    !=  'diagnostic_items_'
                && substr($permission->title, 0, 8)     !=  'billing_'
                && substr($permission->title, 0, 27)    !=  'diagnostic_pathology_bills_'
                && substr($permission->title, 0, 18)    !=  'pathology_reports_'
                && substr($permission->title, 0, 22)    !=  'pathology_due_reports_'
                && substr($permission->title, 0, 23)    !=  'pathology_paid_reports_'
                && substr($permission->title, 0, 19)    !=  'diagnostic_reports_'
                && substr($permission->title, 0, 16)    !=  'database_backup_'
                && substr($permission->title, 0, 15)    !=  'dashboard_data_'
            ;
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);

        // Assgining lab officials permission
        $lab_officials_permissions = $admin_permissions->filter(function ($permission) {
            return
                substr($permission->title, 0, 5)        !=  'user_' 
                && substr($permission->title, 0, 5)     !=  'role_' 
                && substr($permission->title, 0, 11)    !=  'permission_'  
                && substr($permission->title, 0, 9)     !=  'settings_' 
                && substr($permission->title, 0, 9)     !=  'patients_' 
                && substr($permission->title, 0, 8)     !=  'doctors_' 
                && substr($permission->title, 0, 10)    !=  'employees_' 
                && substr($permission->title, 0, 22)    !=  'diagnostic_categories_'
                && substr($permission->title, 0, 17)    !=  'diagnostic_items_'
                && substr($permission->title, 0, 8)     !=  'billing_'
                && substr($permission->title, 0, 16)     !=  'pathology_access'
                && substr($permission->title, 0, 27)    !=  'diagnostic_pathology_bills_'
                && substr($permission->title, 0, 22)    !=  'pathology_due_reports_'
                && substr($permission->title, 0, 23)    !=  'pathology_paid_reports_'
                && substr($permission->title, 0, 16)    !=  'database_backup_'
                && substr($permission->title, 0, 15)    !=  'dashboard_data_'
            ;
        });
        Role::findOrFail(3)->permissions()->sync($lab_officials_permissions);

        // Assgining Accounts permission
        $accounts_permissions = $admin_permissions->filter(function ($permission) {
            return
            substr($permission->title, 0, 5)        !=  'user_' 
            && substr($permission->title, 0, 5)     !=  'role_' 
            && substr($permission->title, 0, 11)    !=  'permission_'  
            && substr($permission->title, 0, 9)     !=  'settings_' 
            && substr($permission->title, 0, 10)    !=  'employees_' 
            && substr($permission->title, 0, 22)    !=  'investigation_reports_'
            && substr($permission->title, 0, 28)    !=  'diagnostic_report_templates_'
            && substr($permission->title, 0, 18)    !=  'pathology_reports_'
            && substr($permission->title, 0, 22)    !=  'pathology_due_reports_'
            && substr($permission->title, 0, 23)    !=  'pathology_paid_reports_'
            && substr($permission->title, 0, 19)    !=  'diagnostic_reports_'
            && substr($permission->title, 0, 16)    !=  'database_backup_'
            ;
        });
        Role::findOrFail(4)->permissions()->sync($accounts_permissions);

         // Assgining Front-Desk permission
         $front_desk_permissions = $admin_permissions->filter(function ($permission) {
            return
                substr($permission->title, 0, 5)        !=  'user_' 
                && substr($permission->title, 0, 5)     !=  'role_' 
                && substr($permission->title, 0, 11)    !=  'permission_'  
                && substr($permission->title, 0, 9)     !=  'settings_' 
                && substr($permission->title, 0, 10)    !=  'employees_' 
                && substr($permission->title, 0, 28)    !=  'diagnostic_report_templates_'
                && substr($permission->title, 0, 24)    !=  'pathology_reports_create'
                && substr($permission->title, 0, 22)    !=  'pathology_reports_edit'
                && substr($permission->title, 0, 22)    !=  'diagnostic_categories_'
                && substr($permission->title, 0, 17)    !=  'diagnostic_items_'
                && substr($permission->title, 0, 24)    !=  'pathology_reports_delete'
                && substr($permission->title, 0, 22)    !=  'pathology_due_reports_'
                && substr($permission->title, 0, 23)    !=  'pathology_paid_reports_'
                && substr($permission->title, 0, 16)    !=  'database_backup_'
            ;
        });
        Role::findOrFail(5)->permissions()->sync($front_desk_permissions);
    }
}
