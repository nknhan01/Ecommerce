<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cms:plugin:activate:all');

        $this->call(BrandSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(ProductCollectionSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(ProductTagSeeder::class);
        $this->call(FlashSaleSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(SimpleSliderSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(ThemeOptionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(StoreLocatorSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(WidgetSeeder::class);
    }
}
