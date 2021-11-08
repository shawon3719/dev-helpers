<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Super Admin',
            ],
            [
                'id'    => 2,
                'title' => 'User',
            ],
            [
                'id'    => 3,
                'title' => 'Lab Official Executive',
            ],
            [
                'id'    => 4,
                'title' => 'Account Executive',
            ],
            [
                'id'    => 5,
                'title' => 'Front Desk Executive',
            ],
        ];

        Role::insert($roles);
    }
}
