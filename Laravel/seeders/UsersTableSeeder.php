<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Super Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id'             => 2,
                'name'           => 'Lab Officials',
                'email'          => 'lab_officials@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id'             => 3,
                'name'           => 'Accounts',
                'email'          => 'account@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
            [
                'id'             => 4,
                'name'           => 'Front Desk',
                'email'          => 'front_desk@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
            ],
        ];

       User::insert($users);
        $users = User::factory()->count(1000)->create();
        foreach($users as $user){
            $user->roles()->sync(2);
        }
       
        
        

    }
}
