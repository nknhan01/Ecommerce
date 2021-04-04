<?php

namespace Database\Seeders;

use Platform\Base\Models\MetaBox as MetaBoxModel;
use Platform\Base\Supports\BaseSeeder;
use Platform\Page\Models\Page;
use Platform\Slug\Models\Slug;
use Faker\Factory;
use Html;
use Illuminate\Support\Str;
use SlugHelper;

class PageSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $pages = [
            [
                'name'     => 'Home',
                'content'  =>
                    Html::tag('div', '[simple-slider key="home-slider"][/simple-slider]') .
                    Html::tag('div',
                        '[featured-product-categories title="Top Categories" description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus blandit massa enim Nullam nunc varius."][/featured-product-categories]') .
                    Html::tag('div', '[flash-sale title="Deal of the day" flash_sale_id="1"][/flash-sale]') .
                    Html::tag('div',
                        '[product-collections title="Exclusive Products"][/product-collections]') .
                    Html::tag('div',
                        '[banners image1="general/b-1.jpg" url1="/product-categories/headphone" image2="general/b-2.jpg" url2="/product-categories/camera" image3="general/b-3.jpg" url3="/product-categories/watches"][/banners]') .
                    Html::tag('div',
                        '[trending-products title="Trending Products"][[/trending-products]') .
                    Html::tag('div', '[product-blocks featured_product_title="Featured Products" top_rated_product_title="Top Rated Products" on_sale_product_title="On Sale Products"][/product-blocks]') .
                    Html::tag('div',
                        '[featured-brands title="Our Brands"][/featured-brands]') .
                    Html::tag('div', '[featured-news title="Visit Our Blog" description="Our Blog updated the newest trend of the world regularly"][/featured-news]') .
                    Html::tag('div', '[testimonials title="Our Client Say!"][/testimonials]') .
                    Html::tag('div', '[our-features icon1="flaticon-shipped" title1="Free Delivery" description1="Free shipping on all US order or order above $200" icon2="flaticon-money-back" title2="30 Day Returns Guarantee" description2="Simply return it within 30 days for an exchange" icon3="flaticon-support" title3="27/4 Online Support" description3="Contact us 24 hours a day, 7 days a week"][/our-features]') .
                    Html::tag('div', '[newsletter-form title="Join Our Newsletter Now" description="Register now to get updates on promotions."][/newsletter-form]')
                ,
                'template' => 'homepage',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Contact us',
                'content'  => Html::tag('p', '[contact-form][/contact-form]'),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Blog',
                'content'  => Html::tag('p', '---'),
                'template' => 'blog-sidebar',
                'user_id'  => 1,
            ],
            [
                'name'     => 'About us',
                'content'  => Html::tag('p', $faker->realText(500)),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'FAQ',
                'content'  => Html::tag('p', $faker->realText(500)),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Location',
                'content'  => Html::tag('p', $faker->realText(500)),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Affiliates',
                'content'  => Html::tag('p', $faker->realText(500)),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Brands',
                'content'  => Html::tag('p', '[all-brands][/all-brands]'),
                'template' => 'default',
                'user_id'  => 1,
            ],
            [
                'name'     => 'Cookie Policy',
                'content'  => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag('p', 'To use this Website we are using Cookies and collecting some Data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.') .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag('p', 'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.') .
                    Html::tag('p', '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.') .
                    Html::tag('p', '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'),
                'template' => 'default',
                'user_id'  => 1,
            ],
        ];

        Page::truncate();
        Slug::where('reference_type', Page::class)->delete();
        MetaBoxModel::where('reference_type', Page::class)->delete();

        foreach ($pages as $index => $item) {
            $page = Page::create($item);

            Slug::create([
                'reference_type' => Page::class,
                'reference_id'   => $page->id,
                'key'            => Str::slug($page->name),
                'prefix'         => SlugHelper::getPrefix(Page::class),
            ]);
        }
    }
}
