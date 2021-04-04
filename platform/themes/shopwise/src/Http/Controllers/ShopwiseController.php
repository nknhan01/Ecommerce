<?php

namespace Theme\Shopwise\Http\Controllers;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Base\Http\Responses\BaseHttpResponse;
use Platform\Blog\Repositories\Interfaces\PostInterface;
use Platform\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Platform\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Platform\Testimonial\Repositories\Interfaces\TestimonialInterface;
use Platform\Theme\Http\Controllers\PublicController;
use Cart;
use Illuminate\Http\Request;
use Theme;
use Theme\Shopwise\Http\Resources\ReviewResource;
use Theme\Shopwise\Http\Resources\BrandResource;
use Theme\Shopwise\Http\Resources\PostResource;
use Theme\Shopwise\Http\Resources\ProductCategoryResource;
use Theme\Shopwise\Http\Resources\TestimonialResource;

class ShopwiseController extends PublicController
{
    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetProducts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $products = get_products_by_collections([
            'collections' => [
                'by'       => 'id',
                'value_in' => [$request->input('collection_id')],
            ],
            'take'        => 10,
            'with'        => [
                'slugable',
                'variations',
                'productCollections',
                'variationAttributeSwatchesForProductList',
                'promotions',
            ],
        ]);

        $data = [];
        foreach ($products as $product) {
            $data[] = Theme::partial('product-item', compact('product'));
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getFeaturedProductCategories(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $categories = get_featured_product_categories();

        return $response->setData(ProductCategoryResource::collection($categories));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetTrendingProducts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $products = get_trending_products([
            'take' => 10,
            'with' => [
                'slugable',
                'variations',
                'productCollections',
                'variationAttributeSwatchesForProductList',
                'promotions',
            ],
        ]);

        $data = [];
        foreach ($products as $product) {
            $data[] = Theme::partial('product-item', compact('product'));
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetFeaturedBrands(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $brands = get_featured_brands();

        return $response->setData(BrandResource::collection($brands));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetFeaturedProducts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $data = [];

        $products = get_featured_products([
            'take' => 10,
            'with' => [
                'slugable',
                'variations',
                'productCollections',
                'variationAttributeSwatchesForProductList',
                'promotions',
            ],
        ]);

        foreach ($products->chunk(3) as $chunk) {
            $item = '';
            foreach ($chunk as $product) {
                $item .= Theme::partial('product-item', compact('product'));
            }
            $data[] = $item;
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetTopRatedProducts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $products = get_top_rated_products(10, [
            'slugable',
            'variations',
            'productCollections',
            'variationAttributeSwatchesForProductList',
            'promotions',
        ]);

        $data = [];
        foreach ($products->chunk(3) as $chunk) {
            $item = '';
            foreach ($chunk as $product) {
                $item .= Theme::partial('product-item', compact('product'));
            }
            $data[] = $item;
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetOnSaleProducts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $products = get_products_on_sale([
            'take' => 10,
            'with' => [
                'slugable',
                'variations',
                'productCollections',
                'variationAttributeSwatchesForProductList',
                'promotions',
            ],
        ]);

        $data = [];
        foreach ($products->chunk(3) as $chunk) {
            $item = '';
            foreach ($chunk as $product) {
                $item .= Theme::partial('product-item', compact('product'));
            }
            $data[] = $item;
        }

        return $response->setData($data);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxCart(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        return $response->setData([
            'count' => Cart::instance('cart')->count(),
            'html'  => Theme::partial('cart'),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function getQuickView(Request $request, $id)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $product = get_products([
            'condition' => [
                'ec_products.id'     => $id,
                'ec_products.status' => BaseStatusEnum::PUBLISHED,
            ],
            'take'      => 1,
            'with'      => [
                'defaultProductAttributes',
                'slugable',
                'tags',
                'tags.slugable',
            ],
        ]);

        if (!$product) {
            abort(404);
        }

        return Theme::partial('quick-view', compact('product'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function ajaxGetFeaturedPosts(Request $request, BaseHttpResponse $response)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $posts = app(PostInterface::class)->getFeatured(3);

        return $response
            ->setData(PostResource::collection($posts))
            ->toApiResponse();
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param TestimonialInterface $testimonialRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetTestimonials(Request $request, BaseHttpResponse $response, TestimonialInterface $testimonialRepository)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $testimonials = $testimonialRepository->allBy(['status' => BaseStatusEnum::PUBLISHED]);

        return $response->setData(TestimonialResource::collection($testimonials));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param ReviewInterface $reviewRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetProductReviews($id, Request $request, BaseHttpResponse $response, ReviewInterface $reviewRepository)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $reviews = $reviewRepository->advancedGet([
            'condition' => [
                'status'     => BaseStatusEnum::PUBLISHED,
                'product_id' => $id,
            ],
            'order_by' => ['created_at' => 'desc'],
            'paginate'  => [
                'per_page'      => (int) $request->input('per_page', 10),
                'current_paged' => (int) $request->input('page', 1),
            ],
        ]);

        return $response->setData(ReviewResource::collection($reviews))->toApiResponse();
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @param FlashSaleInterface $flashSaleRepository
     * @return BaseHttpResponse
     */
    public function ajaxGetFlashSale(Request $request, $id, BaseHttpResponse $response, FlashSaleInterface $flashSaleRepository)
    {
        if (!$request->ajax() || !$request->wantsJson()) {
            abort(404);
        }

        $flashSale = $flashSaleRepository->getModel()
            ->notExpired()
            ->where('id', $id)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->with([
                'products' => function ($query) {
                    return $query->where('status', BaseStatusEnum::PUBLISHED);
                },
            ])
            ->first();

        if (!$flashSale) {
            return $response->setData([]);
        }

        $data = [];
        foreach ($flashSale->products as $product) {
            $data[] = Theme::partial('flash-sale-product', compact('product', 'flashSale'));
        }

        return $response->setData($data);
    }
}
