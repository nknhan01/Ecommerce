<?php

namespace Database\Seeders;

use Platform\Base\Supports\BaseSeeder;
use Platform\Blog\Models\Category;
use Platform\Blog\Models\Tag;
use Platform\Ecommerce\Models\Brand;
use Platform\Ecommerce\Models\ProductCategory;
use Platform\Ecommerce\Models\ProductTag;
use Platform\Menu\Models\Menu as MenuModel;
use Platform\Menu\Models\MenuLocation;
use Platform\Menu\Models\MenuNode;
use Platform\Page\Models\Page;
use Illuminate\Support\Arr;
use Menu;

class MenuSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name'     => 'Main menu',
                'slug'     => 'main-menu',
                'location' => 'main-menu',
                'items'    => [
                    [
                        'title' => 'Home',
                        'url'   => '/',
                    ],
                    [
                        'title' => 'Products',
                        'url'   => '/products',
                    ],
                    [
                        'title'    => 'Shop',
                        'url'      => '#',
                        'children' => [
                            [
                                'title'          => 'Product Category',
                                'reference_id'   => 1,
                                'reference_type' => ProductCategory::class,
                            ],
                            [
                                'title'          => 'Brand',
                                'reference_id'   => 1,
                                'reference_type' => Brand::class,
                            ],
                            [
                                'title'          => 'Product Tag',
                                'reference_id'   => 1,
                                'reference_type' => ProductTag::class,
                            ],
                            [
                                'title' => 'Product Single',
                                'url'   => 'products/beat-headphone',
                            ],
                        ],
                    ],
                    [
                        'title'          => 'Blog',
                        'reference_id'   => 3,
                        'reference_type' => Page::class,
                        'children'       => [
                            [
                                'title'          => 'Blog Left Sidebar',
                                'reference_id'   => 3,
                                'reference_type' => Page::class,
                            ],
                            [
                                'title'          => 'Blog Category',
                                'reference_id'   => 1,
                                'reference_type' => Category::class,
                            ],
                            [
                                'title'          => 'Blog Tag',
                                'reference_id'   => 1,
                                'reference_type' => Tag::class,
                            ],
                            [
                                'title' => 'Blog Single',
                                'url'   => 'news/4-expert-tips-on-how-to-choose-the-right-mens-wallet',
                            ],
                        ],
                    ],
                    [
                        'title'          => 'Contact us',
                        'reference_id'   => 2,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name'  => 'Useful Links',
                'slug'  => 'useful-links',
                'items' => [
                    [
                        'title'          => 'About Us',
                        'reference_id'   => 4,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title'          => 'FAQ',
                        'reference_id'   => 5,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title'          => 'Location',
                        'reference_id'   => 6,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title'          => 'Affiliates',
                        'reference_id'   => 7,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title'          => 'Contact',
                        'reference_id'   => 2,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name'  => 'Categories',
                'slug'  => 'categories',
                'items' => [
                    [
                        'title'          => 'Television',
                        'reference_id'   => 1,
                        'reference_type' => ProductCategory::class,
                    ],
                    [
                        'title'          => 'Mobile',
                        'reference_id'   => 2,
                        'reference_type' => ProductCategory::class,
                    ],
                    [
                        'title'          => 'Headphone',
                        'reference_id'   => 3,
                        'reference_type' => ProductCategory::class,
                    ],
                    [
                        'title'          => 'Watches',
                        'reference_id'   => 4,
                        'reference_type' => ProductCategory::class,
                    ],
                    [
                        'title'          => 'Game',
                        'reference_id'   => 5,
                        'reference_type' => ProductCategory::class,
                    ],
                ],
            ],
            [
                'name'  => 'My Account',
                'slug'  => 'my-account',
                'items' => [
                    [
                        'title' => 'My profile',
                        'url'   => '/customer/overview',
                    ],
                    [
                        'title' => 'Wishlist',
                        'url'   => '/wishlist',
                    ],
                    [
                        'title' => 'Orders',
                        'url'   => 'customer/orders',
                    ],
                    [
                        'title' => 'Order tracking',
                        'url'   => '/orders/tracking',
                    ],
                ],
            ],
        ];

        MenuModel::truncate();
        MenuLocation::truncate();
        MenuNode::truncate();

        foreach ($menus as $index => $item) {
            $menu = MenuModel::create(Arr::except($item, ['items', 'location']));

            if (isset($item['location'])) {
                MenuLocation::create([
                    'menu_id'  => $menu->id,
                    'location' => $item['location'],
                ]);
            }

            foreach ($item['items'] as $menuNode) {
                $this->createMenuNode($index, $menuNode);
            }

        }

        Menu::clearCacheMenuItems();
    }

    /**
     * @param int $index
     * @param array $menuNode
     * @param int $parentId
     */
    protected function createMenuNode(int $index, array $menuNode, int $parentId = 0): void
    {
        $menuNode['menu_id'] = $index + 1;
        $menuNode['parent_id'] = $parentId;

        if (Arr::has($menuNode, 'children')) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;

            unset($menuNode['children']);
        } else {
            $children = [];
            $menuNode['has_child'] = false;
        }

        $createdNode = MenuNode::create($menuNode);

        if ($children) {
            foreach ($children as $child) {
                $this->createMenuNode($index, $child, $createdNode->id);
            }
        }
    }
}
