<?php

namespace Botble\Member\Seeders;

use Botble\Member\Models\Member;
use Faker\Factory;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        Member::truncate();

        Member::create([
            'first_name'   => $faker->firstName,
            'last_name'    => $faker->lastName,
            'email'        => 'john.smith@botble.com',
            'password'     => bcrypt('12345678'),
            'dob'          => $faker->dateTime,
            'phone'        => $faker->phoneNumber,
            'description'  => $faker->realText(30),
            'confirmed_at' => now(),
        ]);

        for ($index = 0; $index < 10; $index++) {
            Member::create([
                'first_name'   => $faker->firstName,
                'last_name'    => $faker->lastName,
                'email'        => $faker->email,
                'password'     => bcrypt($faker->password),
                'dob'          => $faker->dateTime,
                'phone'        => $faker->phoneNumber,
                'description'  => $faker->realText(30),
                'confirmed_at' => now(),
            ]);
        }
    }
}
