<?php

namespace Database\Seeders;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Supports\BaseSeeder;
use Platform\Ecommerce\Models\Order;
use Platform\Ecommerce\Models\OrderAddress;
use Platform\Ecommerce\Models\OrderHistory;
use Platform\Ecommerce\Models\OrderProduct;
use Platform\Ecommerce\Models\Product;
use Platform\Ecommerce\Models\ProductVariation;
use Platform\Ecommerce\Models\ProductVariationItem;
use Platform\Ecommerce\Models\Shipment;
use Platform\Ecommerce\Models\ShipmentHistory;
use Platform\Ecommerce\Models\Wishlist;
use Platform\Slug\Models\Slug;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SlugHelper;

class ProductSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('products');

        $faker = Factory::create();

        $products = [
            [
                'name'           => 'Dual Camera 20MP',
                'images'         => json_encode(['products/7-p.jpg', 'products/hover-7-p.jpg']),
                'price'          => 80.25,
                'sale_price'     => 55,
                'is_featured'    => true,
            ],
            [
                'name'           => 'Smart Watches',
                'images'         => json_encode(['products/8-p.jpg', 'products/hover-8-p.jpg']),
                'price'          => 40.5,
                'sale_price'     => 35,
                'is_featured'    => true,
            ],
            [
                'name'           => 'Beat Headphone',
                'images'         => json_encode(['products/9-p.jpg', 'products/hover-9-p.jpg']),
                'price'          => 20,
                'sale_price'     => 15,
                'is_featured'    => true,
            ],
            [
                'name'   => 'Red & Black Headphone',
                'images' => json_encode(['products/1-p.jpg', 'products/hover-1-p.jpg']),
                'price'  => $faker->numberBetween(50, 60),
            ],
            [
                'name'       => 'Smart Watch External',
                'images'     => json_encode(['products/2-p.jpg', 'products/hover-2-p.jpg']),
                'price'      => $faker->numberBetween(70, 90),
                'sale_price' => $faker->numberBetween(50, 60),
            ],
            [
                'name'       => 'Nikon HD camera',
                'images'     => json_encode(['products/3-p.jpg', 'products/hover-3-p.jpg']),
                'price'      => $faker->numberBetween(40, 50),
                'sale_price' => $faker->numberBetween(30, 40),
            ],
            [
                'name'   => 'Audio Equipment',
                'images' => json_encode(['products/4-p.jpg', 'products/hover-4-p.jpg']),
                'price'  => $faker->numberBetween(50, 60),
            ],
            [
                'name'       => 'Smart Televisions',
                'images'     => json_encode(['products/5-p.jpg', 'products/hover-5-p.jpg']),
                'price'      => $faker->numberBetween(110, 130),
                'sale_price' => $faker->numberBetween(80, 100),
            ],
            [
                'name'   => 'Samsung Smart Phone',
                'images' => json_encode(['products/6-p.jpg', 'products/hover-6-p.jpg']),
                'price'  => $faker->numberBetween(50, 60),
            ],
        ];

        Product::truncate();
        DB::table('ec_product_with_attribute_set')->truncate();
        DB::table('ec_product_with_attribute')->truncate();
        DB::table('ec_product_variations')->truncate();
        DB::table('ec_product_variation_items')->truncate();
        DB::table('ec_product_collection_products')->truncate();
        DB::table('ec_product_category_product')->truncate();
        DB::table('ec_product_related_relations')->truncate();
        Slug::where('reference_type', Product::class)->delete();
        Wishlist::truncate();
        Order::truncate();
        OrderAddress::truncate();
        OrderProduct::truncate();
        OrderHistory::truncate();
        Shipment::truncate();
        ShipmentHistory::truncate();

        foreach ($products as $item) {
            $item['description'] = '<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline.</p>';
            $item['content'] = '<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains&rsquo; signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>
                                <p>- Casual unisex fit</p>

                                <p>- 64% polyester, 36% polyurethane</p>

                                <p>- Water column pressure: 4000 mm</p>

                                <p>- Model is 187cm tall and wearing a size S / M</p>

                                <p>- Unisex fit</p>

                                <p>- Drawstring hood with built-in cap</p>

                                <p>- Front placket with snap buttons</p>

                                <p>- Ventilation under armpit</p>

                                <p>- Adjustable cuffs</p>

                                <p>- Double welted front pockets</p>

                                <p>- Adjustable elastic string at hempen</p>

                                <p>- Ultrasonically welded seams</p>

                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>';
            $item['status'] = BaseStatusEnum::PUBLISHED;
            $item['sku'] = 'SW-' . $faker->numberBetween(100, 200);
            $item['brand_id'] = $faker->numberBetween(1, 7);
            $item['tax_id'] = 1;
            $item['views'] = $faker->numberBetween(1000, 200000);
            $item['quantity'] = $faker->numberBetween(10, 20);
            $item['length'] = $faker->numberBetween(10, 20);
            $item['wide'] = $faker->numberBetween(10, 20);
            $item['height'] = $faker->numberBetween(10, 20);
            $item['weight'] = $faker->numberBetween(500, 900);
            $item['with_storehouse_management'] = true;

            $product = Product::create($item);

            $product->productCollections()->sync([$faker->numberBetween(1, 3)]);
            $product->categories()->sync([
                $faker->numberBetween(1, 14),
                $faker->numberBetween(1, 14),
                $faker->numberBetween(1, 14),
            ]);
            $product->tags()->sync([
                $faker->numberBetween(1, 6),
                $faker->numberBetween(1, 6),
                $faker->numberBetween(1, 6),
            ]);

            Slug::create([
                'reference_type' => Product::class,
                'reference_id'   => $product->id,
                'key'            => Str::slug($product->name),
                'prefix'         => SlugHelper::getPrefix(Product::class),
            ]);
        }

        foreach ($products as $key => $item) {
            $product = Product::find($key + 1);
            $product->productAttributeSets()->sync([1, 2]);
            $product->productAttributes()->sync([$faker->numberBetween(1, 5), $faker->numberBetween(6, 10)]);

            $product->crossSales()->sync([
                $this->random(1, count($products), [$product->id]),
                $this->random(1, count($products), [$product->id]),
                $this->random(1, count($products), [$product->id]),
                $this->random(1, count($products), [$product->id]),
            ]);

            for ($j = 0; $j < $faker->numberBetween(1, 5); $j++) {
                $variation = Product::create([
                    'name'                       => $product->name,
                    'status'                     => BaseStatusEnum::PUBLISHED,
                    'sku'                        => $product->sku . '-A' . $j,
                    'quantity'                   => $product->quantity,
                    'weight'                     => $product->weight,
                    'height'                     => $product->height,
                    'wide'                       => $product->wide,
                    'length'                     => $product->length,
                    'price'                      => $product->price,
                    'sale_price'                 => ($product->price - $product->price * $faker->numberBetween(10,
                            30) / 100),
                    'brand_id'                   => $product->brand_id,
                    'with_storehouse_management' => $product->with_storehouse_management,
                    'is_variation'               => true,
                    'images'                     => json_encode([
                        'products/' . $faker->numberBetween(1, 10) . '-p.jpg',
                        'products/hover-' . $faker->numberBetween(1, 10) . '-p.jpg',
                    ]),
                ]);

                $productVariation = ProductVariation::create([
                    'product_id'              => $variation->id,
                    'configurable_product_id' => $product->id,
                    'is_default'              => $j == 0,
                ]);

                if ($productVariation->is_default) {
                    $product->update([
                        'sku'        => $variation->sku,
                        'sale_price' => $variation->sale_price,
                        'images'     => json_encode($variation->images),
                    ]);
                }

                ProductVariationItem::create([
                    'attribute_id' => $faker->numberBetween(1, 5),
                    'variation_id' => $productVariation->id,
                ]);

                ProductVariationItem::create([
                    'attribute_id' => $faker->numberBetween(6, 10),
                    'variation_id' => $productVariation->id,
                ]);
            }
        }
    }

    /**
     * @param $from
     * @param $to
     * @param array $exceptions
     * @return int
     */
    protected function random($from, $to, array $exceptions = [])
    {
        sort($exceptions); // lets us use break; in the foreach reliably
        $number = rand($from, $to - count($exceptions)); // or mt_rand()
        foreach ($exceptions as $exception) {
            if ($number >= $exception) {
                $number++; // make up for the gap
            } else /*if ($number < $exception)*/ {
                break;
            }
        }
        return $number;
    }
}
