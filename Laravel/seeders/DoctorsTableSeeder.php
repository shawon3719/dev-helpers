<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use App\Services\DoctorsService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class DoctorsTableSeeder extends Seeder
{
    protected $doctorsService;

    public function __construct()
    {
        $this->doctorsService = new DoctorsService(); 
    }

    public function run()
    {

        $faker = Factory::create();

        for($i=1; $i<=1000; $i++){
            $code = $this->doctorsService->generateDoctorCode();
            $gender = $faker->randomElement(['male', 'female']);
            Doctor::create(
            [
                'code'          => $code,
                'name'          => $faker->name($gender),
                'email'         => $faker->unique()->safeEmail($gender),
                'sex'           => $gender,
                'last_degree'   => array_rand(Config::get('settings.last_degree'), 1),
                'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'blood_group'   => array_rand(Config::get('settings.blood_group'), 1),
                'phone'         => $faker->regexify('09[0-9]{9}'), // $faker->phoneNumber,
                'alt_phone'     => $faker->phoneNumber,
                'address'       => $faker->address,
                'city'          => $faker->city,
                'district'      => $faker->state,
                'division'      => $faker->state,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),
            ]);
        }
    }
}
