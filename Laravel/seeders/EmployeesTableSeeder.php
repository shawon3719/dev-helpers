<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeesService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class EmployeesTableSeeder extends Seeder
{
    protected $employeeService;

    public function __construct()
    {
        $this->employeeService = new EmployeesService(); 
    }

    public function run()
    {

        $faker = Factory::create();

        for($i=1; $i<=1000; $i++){
            $code = $this->employeeService->generateEmployeeCode();
            $gender = $faker->randomElement(['male', 'female']);
            Employee::create(
            [
                'code'          => $code,
                'name'                  => $faker->name($gender),
                'email'                 => $faker->unique()->safeEmail($gender),
                'gender'                => $gender,
                'date_of_birth'         => $faker->date($format = 'Y-m-d', $max = 'now'),
                'blood_group'           => array_rand(Config::get('settings.blood_group'), 1),
                'phone'                 => $faker->regexify('09[0-9]{9}'), // $faker->phoneNumber,
                'alt_phone'             => $faker->phoneNumber,
                'emergency_contact'     => $faker->phoneNumber,
                'address'               => $faker->address,
                'city'                  => $faker->city,
                'district'              => $faker->state,
                'division'              => $faker->state,
                'joining_date'          => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),
                'NID'                   => $faker->regexify('09[0-9]{9}'),
                'remarks'               => $faker->word,
                'created_by'            => User::inRandomOrder()->first()->id,
                'created_at'            => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),
            ]);
        }
    }
}
