<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i=1; $i <= 50; $i++) {
            User::create([
                'name' => $faker->firstName . ' ' . $faker->lastName ,
                'email' => 'user' . $i . '@app.com',
                'password' => bcrypt(12345678),
                'reputation_point' => $faker->boolean(67)
                    ? $faker->numberBetween(20, 400)
                    : $faker->numberBetween(-50, 50),
            ]);
        }
    }
}
