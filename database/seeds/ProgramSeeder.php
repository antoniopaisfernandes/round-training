<?php

use App\Program;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,25) as $index) {
            DB::table('programs')->insert([
                'name' => $faker->catchPhrase,
                ]);
        }
    }
}
