<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'code'          => 'H21PT000'.$this->faker->unique()->numberBetween(100,9000), //PatientsService::generatePatientCode(),
            'name'          => $this->faker->name($gender),
            'email'         => $this->faker->unique()->safeEmail($gender),
            'sex'           => $gender,
            'date_of_birth' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'blood_group'   => array_rand(Config::get('settings.blood_group'), 1),
            'phone'         => $this->faker->regexify('09[0-9]{9}'), // $this->faker->phoneNumber,
            'alt_phone'     => $this->faker->phoneNumber,
            'address'       => $this->faker->address,
            'city'          => $this->faker->city,
            'district'      => $this->faker->state,
            'division'      => $this->faker->state,
            'created_by'    => User::inRandomOrder()->first()->id,
            'created_at'    => $this->faker->dateTimeBetween('-2 years', 'now', 'UTC'),
        ];
    }
}
