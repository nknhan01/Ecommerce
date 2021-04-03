<?php

namespace Platform\Ecommerce\Providers;

use Platform\Base\Supports\Helper;
use Platform\Base\Traits\LoadAndPublishDataTrait;
use Platform\Ecommerce\Facades\CartFacade;
use Platform\Ecommerce\Facades\EcommerceHelperFacade;
use Platform\Ecommerce\Facades\OrderHelperFacade;
use Platform\Ecommerce\Http\Middleware\RedirectIfCustomer;
use Platform\Ecommerce\Http\Middleware\RedirectIfNotCustomer;
use Platform\Ecommerce\Models\Address;
use Platform\Ecommerce\Models\Brand;
use Platform\Ecommerce\Models\Currency;
use Platform\Ecommerce\Models\Customer;
use Platform\Ecommerce\Models\Discount;
use Platform\Ecommerce\Models\FlashSale;
use Platform\Ecommerce\Models\GroupedProduct;
use Platform\Ecommerce\Models\Order;
use Platform\Ecommerce\Models\OrderAddress;
use Platform\Ecommerce\Models\OrderHistory;
use Platform\Ecommerce\Models\OrderProduct;
use Platform\Ecommerce\Models\Product;
use Platform\Ecommerce\Models\ProductAttribute;
use Platform\Ecommerce\Models\ProductAttributeSet;
use Platform\Ecommerce\Models\ProductCategory;
use Platform\Ecommerce\Models\ProductCollection;
use Platform\Ecommerce\Models\ProductTag;
use Platform\Ecommerce\Models\ProductVariation;
use Platform\Ecommerce\Models\ProductVariationItem;
use Platform\Ecommerce\Models\Review;
use Platform\Ecommerce\Models\Shipment;
use Platform\Ecommerce\Models\ShipmentHistory;
use Platform\Ecommerce\Models\Shipping;
use Platform\Ecommerce\Models\ShippingRule;
use Platform\Ecommerce\Models\ShippingRuleItem;
use Platform\Ecommerce\Models\StoreLocator;
use Platform\Ecommerce\Models\Tax;
use Platform\Ecommerce\Models\Wishlist;
use Platform\Ecommerce\Repositories\Caches\AddressCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\BrandCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\CurrencyCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\CustomerCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\DiscountCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\FlashSaleCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\GroupedProductCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\OrderAddressCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\OrderCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\OrderHistoryCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\OrderProductCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductAttributeCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductAttributeSetCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductCategoryCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductCollectionCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductTagCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductVariationCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ProductVariationItemCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ReviewCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ShipmentCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ShipmentHistoryCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ShippingCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ShippingRuleCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\ShippingRuleItemCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\StoreLocatorCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\TaxCacheDecorator;
use Platform\Ecommerce\Repositories\Caches\WishlistCacheDecorator;
use Platform\Ecommerce\Repositories\Eloquent\AddressRepository;
use Platform\Ecommerce\Repositories\Eloquent\BrandRepository;
use Platform\Ecommerce\Repositories\Eloquent\CurrencyRepository;
use Platform\Ecommerce\Repositories\Eloquent\CustomerRepository;
use Platform\Ecommerce\Repositories\Eloquent\DiscountRepository;
use Platform\Ecommerce\Repositories\Eloquent\FlashSaleRepository;
use Platform\Ecommerce\Repositories\Eloquent\GroupedProductRepository;
use Platform\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Platform\Ecommerce\Repositories\Eloquent\OrderHistoryRepository;
use Platform\Ecommerce\Repositories\Eloquent\OrderProductRepository;
use Platform\Ecommerce\Repositories\Eloquent\OrderRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductAttributeRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductCollectionRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductTagRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductVariationItemRepository;
use Platform\Ecommerce\Repositories\Eloquent\ProductVariationRepository;
use Platform\Ecommerce\Repositories\Eloquent\ReviewRepository;
use Platform\Ecommerce\Repositories\Eloquent\ShipmentHistoryRepository;
use Platform\Ecommerce\Repositories\Eloquent\ShipmentRepository;
use Platform\Ecommerce\Repositories\Eloquent\ShippingRepository;
use Platform\Ecommerce\Repositories\Eloquent\ShippingRuleItemRepository;
use Platform\Ecommerce\Repositories\Eloquent\ShippingRuleRepository;
use Platform\Ecommerce\Repositories\Eloquent\StoreLocatorRepository;
use Platform\Ecommerce\Repositories\Eloquent\TaxRepository;
use Platform\Ecommerce\Repositories\Eloquent\WishlistRepository;
use Platform\Ecommerce\Repositories\Interfaces\AddressInterface;
use Platform\Ecommerce\Repositories\Interfaces\BrandInterface;
use Platform\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Platform\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Platform\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Platform\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Platform\Ecommerce\Repositories\Interfaces\GroupedProductInterface;
use Platform\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Platform\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Platform\Ecommerce\Repositories\Interfaces\OrderInterface;
use Platform\Ecommerce\Repositories\Interfaces\OrderProductInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductTagInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductVariationInterface;
use Platform\Ecommerce\Repositories\Interfaces\ProductVariationItemInterface;
use Platform\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Platform\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Platform\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Platform\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Platform\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Platform\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Platform\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Platform\Ecommerce\Repositories\Interfaces\TaxInterface;
use Platform\Ecommerce\Repositories\Interfaces\WishlistInterface;
use Platform\Ecommerce\Services\HandleApplyCouponService;
use Platform\Ecommerce\Services\HandleRemoveCouponService;
use Cart;
use EcommerceHelper;
use EmailHandler;
use Event;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;
use SeoHelper;
use SlugHelper;

class EcommerceServiceProvider extends ServiceProvider
{

    use LoadAndPublishDataTrait;

    public function register()
    {
        config([
            'auth.guards.customer'     => [
                'driver'   => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model'  => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table'    => 'ec_customer_password_resets',
                'expire'   => 60,
            ],
        ]);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);

        $this->app->bind(ProductInterface::class, function () {
            return new ProductCacheDecorator(
                new ProductRepository(new Product)
            );
        });

        $this->app->bind(ProductCategoryInterface::class, function () {
            return new ProductCategoryCacheDecorator(
                new ProductCategoryRepository(new ProductCategory)
            );
        });

        $this->app->bind(ProductTagInterface::class, function () {
            return new ProductTagCacheDecorator(
                new ProductTagRepository(new ProductTag)
            );
        });


        $this->app->bind(BrandInterface::class, function () {
            return new BrandCacheDecorator(
                new BrandRepository(new Brand)
            );
        });

        $this->app->bind(ProductCollectionInterface::class, function () {
            return new ProductCollectionCacheDecorator(
                new ProductCollectionRepository(new ProductCollection)
            );
        });

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyCacheDecorator(
                new CurrencyRepository(new Currency)
            );
        });

        $this->app->bind(ProductAttributeSetInterface::class, function () {
            return new ProductAttributeSetCacheDecorator(
                new ProductAttributeSetRepository(new ProductAttributeSet),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductAttributeInterface::class, function () {
            return new ProductAttributeCacheDecorator(
                new ProductAttributeRepository(new ProductAttribute),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationInterface::class, function () {
            return new ProductVariationCacheDecorator(
                new ProductVariationRepository(new ProductVariation),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(ProductVariationItemInterface::class, function () {
            return new ProductVariationItemCacheDecorator(
                new ProductVariationItemRepository(new ProductVariationItem),
                ECOMMERCE_GROUP_CACHE_KEY
            );
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxCacheDecorator(
                new TaxRepository(new Tax)
            );
        });

        $this->app->bind(ReviewInterface::class, function () {
            return new ReviewCacheDecorator(
                new ReviewRepository(new Review)
            );
        });

        $this->app->bind(ShippingInterface::class, function () {
            return new ShippingCacheDecorator(
                new ShippingRepository(new Shipping)
            );
        });

        $this->app->bind(ShippingRuleInterface::class, function () {
            return new ShippingRuleCacheDecorator(
                new ShippingRuleRepository(new ShippingRule)
            );
        });

        $this->app->bind(ShippingRuleItemInterface::class, function () {
            return new ShippingRuleItemCacheDecorator(
                new ShippingRuleItemRepository(new ShippingRuleItem)
            );
        });

        $this->app->bind(ShipmentInterface::class, function () {
            return new ShipmentCacheDecorator(
                new ShipmentRepository(new Shipment)
            );
        });

        $this->app->bind(ShipmentHistoryInterface::class, function () {
            return new ShipmentHistoryCacheDecorator(
                new ShipmentHistoryRepository(new ShipmentHistory)
            );
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderCacheDecorator(
                new OrderRepository(new Order)
            );
        });

        $this->app->bind(OrderHistoryInterface::class, function () {
            return new OrderHistoryCacheDecorator(
                new OrderHistoryRepository(new OrderHistory)
            );
        });

        $this->app->bind(OrderProductInterface::class, function () {
            return new OrderProductCacheDecorator(
                new OrderProductRepository(new OrderProduct)
            );
        });

        $this->app->bind(OrderAddressInterface::class, function () {
            return new OrderAddressCacheDecorator(
                new OrderAddressRepository(new OrderAddress)
            );
        });

        $this->app->bind(DiscountInterface::class, function () {
            return new DiscountCacheDecorator(
                new DiscountRepository(new Discount)
            );
        });

        $this->app->bind(WishlistInterface::class, function () {
            return new WishlistCacheDecorator(
                new WishlistRepository(new Wishlist)
            );
        });

        $this->app->bind(AddressInterface::class, function () {
            return new AddressCacheDecorator(
                new AddressRepository(new Address)
            );
        });
        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerCacheDecorator(
                new CustomerRepository(new Customer)
            );
        });

        $this->app->bind(GroupedProductInterface::class, function () {
            return new GroupedProductCacheDecorator(
                new GroupedProductRepository(new GroupedProduct)
            );
        });

        $this->app->bind(StoreLocatorInterface::class, function () {
            return new StoreLocatorCacheDecorator(
                new StoreLocatorRepository(new StoreLocator)
            );
        });

        $this->app->bind(FlashSaleInterface::class, function () {
            return new FlashSaleCacheDecorator(
                new FlashSaleRepository(new FlashSale)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');

        $loader = AliasLoader::getInstance();
        $loader->alias('Cart', CartFacade::class);
        $loader->alias('OrderHelper', OrderHelperFacade::class);
        $loader->alias('EcommerceHelper', EcommerceHelperFacade::class);

        Event::listen(Logout::class, function () {
            if (config('cart.destroy_on_logout')) {
                $this->app->make(SessionManager::class)->forget('cart');
            }
        });
    }

    public function boot()
    {
        SlugHelper::registerModule(Product::class, 'Products');
        SlugHelper::registerModule(Brand::class, 'Brands');
        SlugHelper::registerModule(ProductCategory::class, 'Product Categories');
        SlugHelper::registerModule(ProductTag::class, 'Product Tags');
        SlugHelper::setPrefix(Product::class, 'products');
        SlugHelper::setPrefix(Brand::class, 'brands');
        SlugHelper::setPrefix(ProductTag::class, 'product-tags');
        SlugHelper::setPrefix(ProductCategory::class, 'product-categories');

        $this->setNamespace('plugins/ecommerce')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes([
                'base',
                'tax',
                'review',
                'shipping',
                'order',
                'discount',
                'customer',
                'payment',
                'cart',
                'shipment',
                'wishlist',
                'compare',
            ])
            ->loadAndPublishConfigurations([
                'general',
                'shipping',
                'order',
                'cart',
                'email',
            ])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce',
                    'priority'    => 8,
                    'parent_id'   => null,
                    'name'        => 'plugins/ecommerce::ecommerce.name',
                    'icon'        => 'fa fa-shopping-cart',
                    'url'         => route('products.index'),
                    'permissions' => ['plugins.ecommerce'],
                ])
                ->registerItem([
                    'id'        => 'cms-plugins-ecommerce-report',
                    'priority'  => 0,
                    'parent_id' => 'cms-plugins-ecommerce',
                    'name'      => 'plugins/ecommerce::reports.name',
                    'icon'      => 'far fa-chart-bar',
                    'url'       => route('ecommerce.report.index'),
                    'permissions' => ['ecommerce.report.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-flash-sale',
                    'priority'    => 0,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::flash-sale.name',
                    'icon'        => 'fa fa-bolt',
                    'url'         => route('flash-sale.index'),
                    'permissions' => ['flash-sale.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-order',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::order.name',
                    'icon'        => 'fa fa-shopping-bag',
                    'url'         => route('orders.index'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-incomplete-order',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::order.incomplete_order',
                    'icon'        => 'fas fa-shopping-basket',
                    'url'         => route('orders.incomplete-list'),
                    'permissions' => ['orders.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce.product',
                    'priority'    => 3,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::products.name',
                    'icon'        => 'fa fa-camera',
                    'url'         => route('products.index'),
                    'permissions' => ['products.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-product-categories',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-categories.name',
                    'icon'        => 'fa fa-archive',
                    'url'         => route('product-categories.index'),
                    'permissions' => ['product-categories.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-product-tag',
                    'priority'    => 4,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-tag.name',
                    'icon'        => 'fa fa-tag',
                    'url'         => route('product-tag.index'),
                    'permissions' => ['product-tag.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-product-attribute',
                    'priority'    => 5,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-attributes.name',
                    'icon'        => 'fas fa-glass-martini',
                    'url'         => route('product-attribute-sets.index'),
                    'permissions' => ['product-attribute-sets.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-brands',
                    'priority'    => 6,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::brands.name',
                    'icon'        => 'fa fa-registered',
                    'url'         => route('brands.index'),
                    'permissions' => ['brands.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-product-collections',
                    'priority'    => 7,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::product-collections.name',
                    'icon'        => 'fa fa-file-excel',
                    'url'         => route('product-collections.index'),
                    'permissions' => ['product-collections.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-ecommerce-review',
                    'priority'    => 9,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::review.name',
                    'icon'        => 'fa fa-comments',
                    'url'         => route('reviews.index'),
                    'permissions' => ['reviews.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-shipping-provider',
                    'priority'    => 10,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::shipping.shipping',
                    'icon'        => 'fas fa-shipping-fast',
                    'url'         => route('shipping_methods.index'),
                    'permissions' => ['shipping_methods.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-discount',
                    'priority'    => 11,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::discount.name',
                    'icon'        => 'fa fa-gift',
                    'url'         => route('discounts.index'),
                    'permissions' => ['discounts.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce-customer',
                    'priority'    => 13,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::customer.name',
                    'icon'        => 'fa fa-users',
                    'url'         => route('customer.index'),
                    'permissions' => ['customer.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-ecommerce.settings',
                    'priority'    => 999,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::ecommerce.settings',
                    'icon'        => 'fas fa-cogs',
                    'url'         => route('ecommerce.settings'),
                    'permissions' => ['ecommerce.settings'],
                ]);

            if (EcommerceHelper::isTaxEnabled()) {
                dashboard_menu()->registerItem([
                    'id'          => 'cms-plugins-ecommerce-tax',
                    'priority'    => 14,
                    'parent_id'   => 'cms-plugins-ecommerce',
                    'name'        => 'plugins/ecommerce::tax.name',
                    'icon'        => 'fas fa-money-check-alt',
                    'url'         => route('tax.index'),
                    'permissions' => ['tax.index'],
                ]);
            }

            EmailHandler::addTemplateSettings(ECOMMERCE_MODULE_SCREEN_NAME, config('plugins.ecommerce.email'));
        });

        $this->app->booted(function () {
            SeoHelper::registerModule([
                Product::class,
                Brand::class,
                ProductCategory::class,
                ProductTag::class,
            ]);
        });

        $this->app->register(EventServiceProvider::class);

        Event::listen(['cart.removed', 'cart.stored', 'cart.restored', 'cart.updated'], function ($cart) {
            $coupon = session('applied_coupon_code');
            if ($coupon) {
                $this->app->make(HandleRemoveCouponService::class)->execute();
                if ($cart->count()) {
                    $this->app->make(HandleApplyCouponService::class)->execute($coupon);
                }
            }
        });
    }
}
