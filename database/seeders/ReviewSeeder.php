<?php

namespace Database\Seeders;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Supports\BaseSeeder;
use Platform\Ecommerce\Models\Review;
use Faker\Factory;

class ReviewSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Review::truncate();

        for ($i = 0; $i < 50; $i++) {
            Review::create([
                'product_id'  => $faker->numberBetween(1, 10),
                'customer_id' => $faker->numberBetween(2, 11),
                'star'        => $faker->numberBetween(1, 5),
                'comment'     => $faker->text(150),
                'status'      => BaseStatusEnum::PUBLISHED,
            ]);
        }
    }
}
