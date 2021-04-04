<?php

namespace Database\Seeders;

use Platform\Base\Supports\BaseSeeder;
use Platform\Ecommerce\Models\ProductCategory;
use Platform\Slug\Models\Slug;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class ProductCategorySeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('product-categories');

        $faker = Factory::create();

        $categories = [
            [
                'name'        => 'Television',
                'icon'        => 'flaticon-tv',
                'image'       => 'product-categories/p-1.png',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                ],
            ],
            [
                'name'        => 'Mobile',
                'icon'        => 'flaticon-responsive',
                'image'       => 'product-categories/p-2.png',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                ],
            ],
            [
                'name'        => 'Headphone',
                'icon'        => 'flaticon-headphones',
                'image'       => 'product-categories/p-3.png',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                ],
            ],
            [
                'name'        => 'Watches',
                'icon'        => 'flaticon-watch',
                'image'       => 'product-categories/p-4.png',
                'is_featured' => true,
                'children'    => [
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                    [
                        'name' => $faker->text(25),
                    ],
                ],
            ],
            [
                'name'        => 'Game',
                'icon'        => 'flaticon-console',
                'image'       => 'product-categories/p-5.png',
                'is_featured' => true,
            ],
            [
                'name'        => 'Camera',
                'icon'        => 'flaticon-camera',
                'image'       => 'product-categories/p-6.png',
                'is_featured' => true,
            ],
            [
                'name'        => 'Audio',
                'icon'        => 'flaticon-music-system',
                'image'       => 'product-categories/p-7.png',
                'is_featured' => true,
            ],
            [
                'name' => 'Mobile & Tablet',
                'icon' => 'flaticon-responsive',
            ],
            [
                'name' => 'Accessories',
                'icon' => 'flaticon-plugins',
            ],
            [
                'name' => 'Home Audio & Theater',
                'icon' => 'flaticon-music-system',
            ],
            [
                'name' => 'Tv & Smart Box',
                'icon' => 'flaticon-monitor',
            ],
            [
                'name' => 'Printer',
                'icon' => 'flaticon-printer',
            ],
            [
                'name' => 'Computer',
                'icon' => 'flaticon-tv',
            ],
            [
                'name' => 'Fax Machine',
                'icon' => 'flaticon-fax',
            ],
            [
                'name' => 'Mouse',
                'icon' => 'flaticon-mouse',
            ],
        ];

        ProductCategory::truncate();
        Slug::where('reference_type', ProductCategory::class)->delete();

        foreach ($categories as $key => $item) {
            $item['order'] = $key;
            $category = ProductCategory::create(Arr::except($item, ['icon', 'children']));

            MetaBox::saveMetaBoxData($category, 'icon', $item['icon']);

            Slug::create([
                'reference_type' => ProductCategory::class,
                'reference_id'   => $category->id,
                'key'            => Str::slug($category->name),
                'prefix'         => SlugHelper::getPrefix(ProductCategory::class),
            ]);
        }

        foreach ($categories as $key => $item) {
            foreach (Arr::get($item, 'children', []) as $child) {
                $child['parent_id'] = $key + 1;
                $child = ProductCategory::create($child);

                Slug::create([
                    'reference_type' => ProductCategory::class,
                    'reference_id'   => $child->id,
                    'key'            => Str::slug($child->name),
                    'prefix'         => SlugHelper::getPrefix(ProductCategory::class),
                ]);
            }
        }
    }
}
