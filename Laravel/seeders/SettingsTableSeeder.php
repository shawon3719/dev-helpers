<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'            => 1,
                'key'           => 'system_logo',
                'created_by'    => 1,
            ],
            [
                'id'            => 2,
                'key'           => 'system_title',
                'created_by'    => 1,
            ],
            [
                'id'            => 3,
                'key'           => 'favicon',
                'created_by'    => 1,
            ],
            [
                'id'            => 4,
                'key'           => 'data_order',
                'created_by'    => 1,
            ],
            [
                'id'            => 5,
                'key'           => 'item_per_page',
                'created_by'    => 1,
            ],
            [
                'id'            => 6,
                'key'           => 'address',
                'created_by'    => 1,
            ],
            [
                'id'            => 7,
                'key'           => 'phone',
                'created_by'    => 1,
            ],
            [
                'id'            => 8,
                'key'           => 'email',
                'created_by'    => 1,
            ],
            [
                'id'            => 9,
                'key'           => 'currency',
                'created_by'    => 1,
            ],
            [
                'id'            => 10,
                'key'           => 'currency_symbol',
                'created_by'    => 1,
            ],
            [
                'id'            => 11,
                'key'           => 'date_format',
                'created_by'    => 1,
            ],
        ];

        Setting::insert($users);
    }
}
