<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 18,
                'title' => 'settings_create',
            ],
            [
                'id'    => 19,
                'title' => 'settings_edit',
            ],
            [
                'id'    => 20,
                'title' => 'settings_show',
            ],
            [
                'id'    => 21,
                'title' => 'settings_delete',
            ],
            [
                'id'    => 22,
                'title' => 'settings_access',
            ],
            [
                'id'    => 23,
                'title' => 'patients_create',
            ],
            [
                'id'    => 24,
                'title' => 'patients_edit',
            ],
            [
                'id'    => 25,
                'title' => 'patients_show',
            ],
            [
                'id'    => 26,
                'title' => 'patients_delete',
            ],
            [
                'id'    => 27,
                'title' => 'patients_access',
            ],
            [
                'id'    => 28,
                'title' => 'doctors_create',
            ],
            [
                'id'    => 29,
                'title' => 'doctors_edit',
            ],
            [
                'id'    => 30,
                'title' => 'doctors_show',
            ],
            [
                'id'    => 31,
                'title' => 'doctors_delete',
            ],
            [
                'id'    => 32,
                'title' => 'doctors_access',
            ],
            [
                'id'    => 33,
                'title' => 'employees_create',
            ],
            [
                'id'    => 34,
                'title' => 'employees_edit',
            ],
            [
                'id'    => 35,
                'title' => 'employees_show',
            ],
            [
                'id'    => 36,
                'title' => 'employees_delete',
            ],
            [
                'id'    => 37,
                'title' => 'employees_access',
            ],
            [
                'id'    => 38,
                'title' => 'diagnostic_categories_create',
            ],
            [
                'id'    => 39,
                'title' => 'diagnostic_categories_edit',
            ],
            [
                'id'    => 40,
                'title' => 'diagnostic_categories_show',
            ],
            [
                'id'    => 41,
                'title' => 'diagnostic_categories_delete',
            ],
            [
                'id'    => 42,
                'title' => 'diagnostic_categories_access',
            ],
            [
                'id'    => 43,
                'title' => 'pathology_access',
            ],
            [
                'id'    => 44,
                'title' => 'diagnostic_items_create',
            ],
            [
                'id'    => 45,
                'title' => 'diagnostic_items_edit',
            ],
            [
                'id'    => 46,
                'title' => 'diagnostic_items_show',
            ],
            [
                'id'    => 47,
                'title' => 'diagnostic_items_delete',
            ],
            [
                'id'    => 48,
                'title' => 'diagnostic_items_access',
            ],
            [
                'id'    => 49,
                'title' => 'investigation_reports_access',
            ],
            [
                'id'    => 50,
                'title' => 'diagnostic_report_templates_create',
            ],
            [
                'id'    => 51,
                'title' => 'diagnostic_report_templates_edit',
            ],
            [
                'id'    => 52,
                'title' => 'diagnostic_report_templates_show',
            ],
            [
                'id'    => 53,
                'title' => 'diagnostic_report_templates_delete',
            ],
            [
                'id'    => 54,
                'title' => 'diagnostic_report_templates_access',
            ],
            [
                'id'    => 55,
                'title' => 'billing_access',
            ],
            [
                'id'    => 56,
                'title' => 'diagnostic_pathology_bills_create',
            ],
            [
                'id'    => 57,
                'title' => 'diagnostic_pathology_bills_edit',
            ],
            [
                'id'    => 58,
                'title' => 'diagnostic_pathology_bills_show',
            ],
            [
                'id'    => 59,
                'title' => 'diagnostic_pathology_bills_delete',
            ],
            [
                'id'    => 60,
                'title' => 'diagnostic_pathology_bills_access',
            ],
            [
                'id'    => 61,
                'title' => 'diagnostic_pathology_bills_invoice_create',
            ],
            [
                'id'    => 62,
                'title' => 'pathology_reports_create',
            ],
            [
                'id'    => 63,
                'title' => 'pathology_reports_edit',
            ],
            [
                'id'    => 64,
                'title' => 'pathology_reports_show',
            ],
            [
                'id'    => 65,
                'title' => 'pathology_reports_delete',
            ],
            [
                'id'    => 66,
                'title' => 'pathology_reports_access',
            ],
            [
                'id'    => 67,
                'title' => 'diagnostic_reports_access',
            ],
            [
                'id'    => 71,
                'title' => 'pathology_due_reports_access',
            ],
            [
                'id'    => 72,
                'title' => 'pathology_paid_reports_access',
            ],
            [
                'id'    => 73,
                'title' => 'database_backup_access',
            ],
            [
                'id'    => 74,
                'title' => 'dashboard_data_access',
            ],
            
        ];

        Permission::insert($permissions);
    }
}
