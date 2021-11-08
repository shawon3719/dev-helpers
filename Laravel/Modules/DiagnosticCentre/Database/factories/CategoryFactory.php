<?php
namespace Modules\DiagnosticCentre\Database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\DiagnosticCentre\Entities\Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code'          => 'H21PC000'.$this->faker->unique()->numberBetween(100,9000),
            'name'          => $this->faker->text,
            'type'          => array_rand(Config::get('diagnosticcentre.category_type'), 1),
            'description'   => $this->faker->sentence,
            'created_by'    => User::inRandomOrder()->first()->id,
            'created_at'    => $this->faker->dateTimeBetween('-2 years', 'now', 'UTC'),
        ];
    }
}

