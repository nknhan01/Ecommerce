<?php

namespace Database\Seeders;

use Platform\Base\Supports\BaseSeeder;
use Platform\Ecommerce\Models\Brand;
use Platform\Slug\Models\Slug;
use Illuminate\Support\Str;
use SlugHelper;

class BrandSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('brands');

        $brands = [
            [
                'name' => 'Fashion live',
            ],
            [
                'name' => 'Hand crafted',
            ],
            [
                'name' => 'Mestonix',
            ],
            [
                'name' => 'Sunshine',
            ],
            [
                'name' => 'Pure',
            ],
            [
                'name' => 'Anfold',
            ],
            [
                'name' => 'Automotive',
            ],
        ];

        Brand::truncate();
        Slug::where('reference_type', Brand::class)->delete();

        foreach ($brands as $key => $item) {
            $item['order'] = $key;
            $item['is_featured'] = true;
            $item['logo'] = 'brands/' . ($key + 1) . '.png';
            $brand = Brand::create($item);

            Slug::create([
                'reference_type' => Brand::class,
                'reference_id'   => $brand->id,
                'key'            => Str::slug($brand->name),
                'prefix'         => SlugHelper::getPrefix(Brand::class),
            ]);
        }
    }
}
