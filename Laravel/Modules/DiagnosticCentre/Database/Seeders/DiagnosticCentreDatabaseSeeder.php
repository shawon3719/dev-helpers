<?php

namespace Modules\DiagnosticCentre\Database\Seeders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DiagnosticCentreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $this->call([
            CategoryTableSeeder::class,
            ItemsTableSeeder::class,
            PathologyBillTableSeeder::class,
        ]);
    }
}
