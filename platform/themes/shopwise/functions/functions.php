<?php

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Ecommerce\Models\ProductCategory;
use Platform\Ecommerce\Repositories\Interfaces\FlashSaleInterface;

register_page_template([
    'homepage'     => __('Homepage'),
    'blog-sidebar' => __('Blog Sidebar'),
]);

register_sidebar([
    'id'          => 'footer_sidebar',
    'name'        => __('Footer sidebar'),
    'description' => __('Sidebar in the footer of site'),
]);

theme_option()
    ->setField([
        'id'         => 'copyright',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Copyright'),
        'attributes' => [
            'name'    => 'copyright',
            'value'   => '© 2020 Laravel Technologies. All right reserved.',
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => __('Change copyright'),
                'data-counter' => 250,
            ],
        ],
        'helper'     => __('Copyright on footer of site'),
    ])
    ->setField([
        'id'         => 'preloader_enabled',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Preloader?'),
        'attributes' => [
            'name'    => 'preloader_enabled',
            'list'    => [
                'no'  => trans('core/base::base.no'),
                'yes' => trans('core/base::base.yes'),
            ],
            'value'   => 'no',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'hotline',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Hotline'),
        'attributes' => [
            'name'    => 'hotline',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Hotline',
                'data-counter' => 30,
            ],
        ],
    ])
    ->setField([
        'id'         => 'address',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Address'),
        'attributes' => [
            'name'    => 'address',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Address',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'email',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'email',
        'label'      => __('Email'),
        'attributes' => [
            'name'    => 'email',
            'value'   => null,
            'options' => [
                'class'        => 'form-control',
                'placeholder'  => 'Email',
                'data-counter' => 120,
            ],
        ],
    ])
    ->setField([
        'id'         => 'about-us',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'textarea',
        'label'      => __('About us'),
        'attributes' => [
            'name'    => 'about-us',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'primary_font',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'googleFonts',
        'label'      => __('Primary font'),
        'attributes' => [
            'name'  => 'primary_font',
            'value' => 'Poppins',
        ],
    ])
    ->setField([
        'id'         => 'primary_color',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'customColor',
        'label'      => __('Primary color'),
        'attributes' => [
            'name'  => 'primary_color',
            'value' => '#FF324D',
        ],
    ])
    ->setField([
        'id'         => 'secondary_color',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'customColor',
        'label'      => __('Secondary color'),
        'attributes' => [
            'name'  => 'secondary_color',
            'value' => '#1D2224',
        ],
    ])
    ->setField([
        'id'         => 'enable_newsletter_popup',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Newsletter popup?'),
        'attributes' => [
            'name'    => 'enable_newsletter_popup',
            'list'    => [
                'no'  => trans('core/base::base.no'),
                'yes' => trans('core/base::base.yes'),
            ],
            'value'   => 'yes',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'newsletter_image',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'mediaImage',
        'label'      => __('Image for newsletter popup'),
        'attributes' => [
            'name'  => 'newsletter_image',
            'value' => null,
        ],
    ])
    ->setField([
        'id'         => 'logo_footer',
        'section_id' => 'opt-text-subsection-logo',
        'type'       => 'mediaImage',
        'label'      => __('Logo in Footer'),
        'attributes' => [
            'name'  => 'logo_footer',
            'value' => null,
        ],
    ])
    ->setSection([
        'title'      => __('Social'),
        'desc'       => __('Social links'),
        'id'         => 'opt-text-subsection-social',
        'subsection' => true,
        'icon'       => 'fa fa-share-alt',
    ])
    ->setField([
        'id'         => 'facebook',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Facebook',
        'attributes' => [
            'name'    => 'facebook',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'twitter',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Twitter',
        'attributes' => [
            'name'    => 'twitter',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'youtube',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Youtube',
        'attributes' => [
            'name'    => 'youtube',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'instagram',
        'section_id' => 'opt-text-subsection-social',
        'type'       => 'text',
        'label'      => 'Instagram',
        'attributes' => [
            'name'    => 'instagram',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'facebook_chat_enabled',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Facebook chat?'),
        'attributes' => [
            'name'    => 'facebook_chat_enabled',
            'list'    => [
                'yes' => trans('core/base::base.yes'),
                'no'  => trans('core/base::base.no'),
            ],
            'value'   => 'yes',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'facebook_page_id',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'text',
        'label'      => __('Facebook page ID'),
        'attributes' => [
            'name'    => 'facebook_page_id',
            'value'   => null,
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'facebook_comment_enabled_in_post',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Facebook comment in post detail page?'),
        'attributes' => [
            'name'    => 'facebook_comment_enabled_in_post',
            'list'    => [
                'yes' => trans('core/base::base.yes'),
                'no'  => trans('core/base::base.no'),
            ],
            'value'   => 'yes',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'facebook_comment_enabled_in_product',
        'section_id' => 'opt-text-subsection-general',
        'type'       => 'select',
        'label'      => __('Enable Facebook comment in product detail page?'),
        'attributes' => [
            'name'    => 'facebook_comment_enabled_in_product',
            'list'    => [
                'yes' => trans('core/base::base.yes'),
                'no'  => trans('core/base::base.no'),
            ],
            'value'   => 'yes',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'payment_methods',
        'section_id' => 'opt-text-subsection-ecommerce',
        'type'       => 'mediaImages',
        'label'      => 'Accepted Payment methods',
        'attributes' => [
            'name'   => 'payment_methods[]',
            'values' => theme_option('payment_methods', []),
        ],
    ])
    ->setSection([
        'title'      => __('Header'),
        'desc'       => __('Options for header'),
        'id'         => 'opt-text-subsection-header',
        'subsection' => true,
        'icon'       => 'fas fa-magic',
    ])
    ->setField([
        'id'         => 'enable_sticky_header',
        'section_id' => 'opt-text-subsection-header',
        'type'       => 'select',
        'label'      => 'Enable sticky header?',
        'attributes' => [
            'name'    => 'enable_sticky_header',
            'list'    => [
                'yes' => trans('core/base::base.yes'),
                'no'  => trans('core/base::base.no'),
            ],
            'value'   => 'yes',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ])
    ->setField([
        'id'         => 'collapsing_product_categories_on_homepage',
        'section_id' => 'opt-text-subsection-header',
        'type'       => 'select',
        'label'      => 'Collapsing product categories on homepage?',
        'attributes' => [
            'name'    => 'collapsing_product_categories_on_homepage',
            'list'    => [
                'yes' => trans('core/base::base.yes'),
                'no'  => trans('core/base::base.no'),
            ],
            'value'   => 'no',
            'options' => [
                'class' => 'form-control',
            ],
        ],
    ]);

add_action('init', function () {
    config([
        'filesystems.disks.public.root' => public_path('storage'),
        'filesystems.disks.public.url'  => str_replace('/index.php', '', url('storage')),
    ]);
}, 124);

RvMedia::addSize('medium', 540, 600)->addSize('small', 540, 300);

if (is_plugin_active('ecommerce')) {
    add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
        if (get_class($object) == ProductCategory::class && $context == 'advanced') {
            MetaBox::addMetaBox('additional_product_category_fields', __('Addition Information'), function () {
                $icon = null;
                $args = func_get_args();
                if (!empty($args[0])) {
                    $icon = MetaBox::getMetaData($args[0], 'icon', true);
                }

                return Theme::partial('product-category-fields', compact('icon'));
            }, get_class($object), $context);
        }
    }, 24, 2);

    add_action(BASE_ACTION_AFTER_CREATE_CONTENT, function ($type, $request, $object) {
        if (get_class($object) == ProductCategory::class) {
            MetaBox::saveMetaBoxData($object, 'icon', $request->input('icon'));
        }
    }, 230, 3);

    add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, function ($type, $request, $object) {
        if (get_class($object) == ProductCategory::class) {
            MetaBox::saveMetaBoxData($object, 'icon', $request->input('icon'));
        }
    }, 231, 3);

    add_shortcode('featured-product-categories', __('Featured Product Categories'), __('Featured Product Categories'),
        function ($shortCode) {

            return Theme::partial('short-codes.featured-product-categories', [
                'title'       => $shortCode->title,
                'description' => $shortCode->description,
            ]);
        });

    shortcode()->setAdminConfig('featured-product-categories',
        Theme::partial('short-codes.featured-product-categories-admin-config'));

    add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function ($shortCode) {
        return Theme::partial('short-codes.featured-brands', [
            'title' => $shortCode->title,
        ]);
    });

    shortcode()->setAdminConfig('featured-brands', Theme::partial('short-codes.featured-brands-admin-config'));

    add_shortcode('product-collections', __('Product Collections'), __('Product Collections'), function ($shortCode) {
        $productCollections = get_product_collections(['status' => BaseStatusEnum::PUBLISHED], [],
            ['id', 'name', 'slug'])->toArray();

        return Theme::partial('short-codes.product-collections', [
            'title'              => $shortCode->title,
            'productCollections' => $productCollections,
        ]);
    });

    shortcode()->setAdminConfig('product-collections',
        Theme::partial('short-codes.product-collections-admin-config'));

    add_shortcode('trending-products', __('Trending Products'), __('Trending Products'), function ($shortCode) {
        return Theme::partial('short-codes.trending-products', [
            'title' => $shortCode->title,
        ]);
    });

    shortcode()->setAdminConfig('trending-products', Theme::partial('short-codes.trending-products-admin-config'));

    add_shortcode('product-blocks', __('Product Blocks'), __('Product Blocks'), function ($shortCode) {
        return Theme::partial('short-codes.product-blocks', [
            'featured_product_title'  => $shortCode->featured_product_title,
            'top_rated_product_title' => $shortCode->top_rated_product_title,
            'on_sale_product_title'   => $shortCode->on_sale_product_title,
        ]);
    });

    shortcode()->setAdminConfig('product-blocks', Theme::partial('short-codes.product-blocks-admin-config'));

    add_shortcode('all-products', __('All Products'), __('All Products'), function ($shortCode) {
        $products = get_products([
            'paginate' => [
                'per_page'      => $shortCode->per_page,
                'current_paged' => (int)request()->input('page'),
            ],
        ]);

        return Theme::partial('short-codes.all-products', [
            'title'    => $shortCode->title,
            'products' => $products,
        ]);
    });

    shortcode()->setAdminConfig('all-products', Theme::partial('short-codes.all-products-admin-config'));

    add_shortcode('all-brands', __('All Brands'), __('All Brands'), function ($shortCode) {
        $brands = get_all_brands();

        return Theme::partial('short-codes.all-brands', [
            'title'  => $shortCode->title,
            'brands' => $brands,
        ]);
    });

    shortcode()->setAdminConfig('all-brands', Theme::partial('short-codes.all-brands-admin-config'));

    add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function ($shortCode) {
        $flashSale = app(FlashSaleInterface::class)->getModel()
            ->where('id', $shortCode->flash_sale_id)
            ->notExpired()
            ->first();

        if (!$flashSale || !$flashSale->products()->count()) {
            return null;
        }

        return Theme::partial('short-codes.flash-sale', [
            'title'     => $shortCode->title,
            'flashSale' => $flashSale,
        ]);
    });

    shortcode()->setAdminConfig('flash-sale', function () {
        $flashSales = app(FlashSaleInterface::class)
            ->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->get();

        return Theme::partial('short-codes.flash-sale-admin-config', compact('flashSales'));
    });

}

add_shortcode('banners', __('Banners'), __('Banners'), function ($shortCode) {
    return Theme::partial('short-codes.banners', [
        'image1' => $shortCode->image1,
        'url1'   => $shortCode->url1,
        'image2' => $shortCode->image2,
        'url2'   => $shortCode->url2,
        'image3' => $shortCode->image3,
        'url3'   => $shortCode->url3,
    ]);
});

shortcode()->setAdminConfig('banners', Theme::partial('short-codes.banners-admin-config'));

add_shortcode('our-features', __('Our features'), __('Our features'), function ($shortCode) {
    return Theme::partial('short-codes.our-features', [
        'icon1'        => $shortCode->icon1,
        'title1'       => $shortCode->title1,
        'description1' => $shortCode->description1,
        'icon2'        => $shortCode->icon2,
        'title2'       => $shortCode->title2,
        'description2' => $shortCode->description2,
        'icon3'        => $shortCode->icon3,
        'title3'       => $shortCode->title3,
        'description3' => $shortCode->description3,
    ]);
});

shortcode()->setAdminConfig('our-features', Theme::partial('short-codes.our-features-admin-config'));

if (is_plugin_active('testimonial')) {
    add_shortcode('testimonials', __('Testimonials'), __('Testimonials'), function ($shortCode) {
        return Theme::partial('short-codes.testimonials', [
            'title' => $shortCode->title,
        ]);
    });

    shortcode()->setAdminConfig('testimonials', Theme::partial('short-codes.testimonials-admin-config'));
}

if (is_plugin_active('newsletter')) {
    add_shortcode('newsletter-form', __('Newsletter Form'), __('Newsletter Form'), function ($shortCode) {
        return Theme::partial('short-codes.newsletter-form', [
            'title'       => $shortCode->title,
            'description' => $shortCode->description,
        ]);
    });

    shortcode()->setAdminConfig('newsletter-form', Theme::partial('short-codes.newsletter-form-admin-config'));
}

if (is_plugin_active('contact')) {
    add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
        return Theme::getThemeNamespace() . '::partials.short-codes.contact-form';
    }, 120);
}

if (is_plugin_active('blog')) {
    add_shortcode('featured-news', __('Featured News'), __('Featured News'), function ($shortCode) {
        return Theme::partial('short-codes.featured-news', [
            'title'       => $shortCode->title,
            'description' => $shortCode->description,
        ]);
    });
    shortcode()->setAdminConfig('featured-news', Theme::partial('short-codes.featured-news-admin-config'));
}

if (is_plugin_active('simple-slider')) {
    add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
        return Theme::getThemeNamespace() . '::partials.short-codes.sliders';
    }, 120);
}

Form::component('themeIcon', Theme::getThemeNamespace() . '::partials.icons-field', [
    'name',
    'value'      => null,
    'attributes' => [],
]);
