<?php

namespace Modules\DiagnosticCentre\Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Modules\DiagnosticCentre\Entities\Category;
use Illuminate\Support\Str;
use Modules\DiagnosticCentre\Services\CategoriesService;

class CategoryTableSeeder extends Seeder
{
    protected $categoriesService;

    public function __construct()
    {
        $this->categoriesService = new CategoriesService(); 
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $categories_data = ([
            [  
                'name'          => Str::ucfirst('Pathology Services'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('Radiology & Imaging Services'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('Test Equipment'),
                'type'          => 'test_equipment',
                'description'   => $faker->sentence,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('BIOCHEMISTRY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('HAEMATOLOGY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('IMMUNOLOGY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('PCR LAB'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('MRI'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('X-RAY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('CT SCAN'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('USG'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('ECG'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('ETT'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('ECHOCARDIOGRAM'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('COLONOSCOPY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 2,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('MICROBIOLOGY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
            [  
                'name'          => Str::ucfirst('CLINICAL PATHOLOGY'),
                'type'          => 'lab_test',
                'description'   => $faker->sentence,
                'parent_id'     => 1,
                'created_by'    => User::inRandomOrder()->first()->id,
                'created_at'    => $faker->dateTimeBetween('-2 years', 'now', 'UTC'),                
            ],
        ]);



        foreach($categories_data as $category){
            $code = $this->categoriesService->generateCategoryCode();
            Category::create(array_merge($category, ['code' => $code]));

        }
    }
}
