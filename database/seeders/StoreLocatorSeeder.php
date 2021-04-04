<?php

namespace Database\Seeders;

use Platform\Ecommerce\Models\StoreLocator;
use Illuminate\Database\Seeder;

class StoreLocatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreLocator::truncate();

        StoreLocator::create([
            'name'                 => 'Shopwise',
            'email'                => 'sales@botble.com',
            'phone'                => '123-456-7890',
            'address'              => '123 Street, Old Trafford',
            'state'                => 'New York',
            'city'                 => 'New York',
            'country'              => 'US',
            'is_primary'           => 1,
            'is_shipping_location' => 1,
        ]);
    }
}
