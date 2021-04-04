<?php

namespace Database\Seeders;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Supports\BaseSeeder;
use Platform\Ecommerce\Models\Tax;

class TaxSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tax::truncate();

        Tax::create([
            'title'      => 'VAT',
            'percentage' => 10,
            'priority'   => 1,
            'status'     => BaseStatusEnum::PUBLISHED,
        ]);

        Tax::create([
            'title'      => 'None',
            'percentage' => 0,
            'priority'   => 2,
            'status'     => BaseStatusEnum::PUBLISHED,
        ]);
    }
}
