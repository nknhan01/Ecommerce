<?php

namespace Database\Seeders;

use Platform\Base\Supports\BaseSeeder;
use Platform\Blog\Models\Category;
use Platform\Blog\Models\Post;
use Platform\Blog\Models\Tag;
use Platform\Setting\Models\Setting as SettingModel;
use Platform\Slug\Models\Slug;
use SlugHelper;

class SettingSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingModel::insertOrIgnore([
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Post::class),
                'value' => 'news',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Category::class),
                'value' => 'news',
            ],
            [
                'key'   => SlugHelper::getPermalinkSettingKey(Tag::class),
                'value' => 'tags',
            ],
            [
                'key'   => 'payment_cod_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_cod_description',
                'value' => 'Please pay money directly to the postman, if you choose cash on delivery method (COD).',
            ],
            [
                'key'   => 'payment_bank_transfer_status',
                'value' => 1,
            ],
            [
                'key'   => 'payment_bank_transfer_description',
                'value' => 'Please send money to our bank account: ACB - 1990 404 19.',
            ],
            [
                'key'   => 'plugins_ecommerce_customer_new_order_status',
                'value' => '0',
            ],
            [
                'key'   => 'plugins_ecommerce_admin_new_order_status',
                'value' => '0',
            ],
            [
                'key'   => 'ecommerce_store_name',
                'value' => 'Shopwise',
            ],
            [
                'key'   => 'ecommerce_store_phone',
                'value' => '123-456-7890',
            ],
            [
                'key'   => 'ecommerce_store_address',
                'value' => '123 Street, Old Trafford',
            ],
            [
                'key'   => 'New York',
                'value' => 'Singapore',
            ],
            [
                'key'   => 'New York',
                'value' => 'Singapore',
            ],
            [
                'key'   => 'ecommerce_store_country',
                'value' => 'US',
            ],
        ]);

        Slug::where('reference_type', Post::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', Category::class)->update(['prefix' => 'news']);
        Slug::where('reference_type', Tag::class)->update(['prefix' => 'tags']);
    }
}
