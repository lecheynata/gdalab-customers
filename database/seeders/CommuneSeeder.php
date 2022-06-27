<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 6; $i++) {
            DB::table('communes')->insert([
                'id_com' => $i + 1,
                'id_reg' => rand(1, 10),
                'description' => $faker->city()
            ]);
        }
    }
}
