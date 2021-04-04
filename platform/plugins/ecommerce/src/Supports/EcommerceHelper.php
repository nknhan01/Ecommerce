<?php

namespace Platform\Ecommerce\Supports;

use Platform\Base\Supports\Helper;
use Exception;

class EcommerceHelper
{
    /**
     * @return bool
     */
    public function isCartEnabled(): bool
    {
        return get_ecommerce_setting('shopping_cart_enabled', '1') == '1';
    }

    /**
     * @return bool
     */
    public function isTaxEnabled(): bool
    {
        return get_ecommerce_setting('ecommerce_tax_enabled', '1') == '1';
    }

    /**
     * @return bool
     */
    public function isReviewEnabled(): bool
    {
        return get_ecommerce_setting('review_enabled', '1') == '1';
    }

    /**
     * @return bool
     */
    public function isQuickBuyButtonEnabled(): bool
    {
        return get_ecommerce_setting('enable_quick_buy_button', '1') == '1';
    }

    /**
     * @return string
     */
    public function getQuickBuyButtonTarget(): string
    {
        return get_ecommerce_setting('quick_buy_target_page', 'checkout');
    }

    /**
     * @return bool
     */
    public function isZipCodeEnabled(): bool
    {
        return get_ecommerce_setting('zip_code_enabled', '0') == '1';
    }

    /**
     * @return bool
     */
    public function isDisplayProductIncludingTaxes(): bool
    {
        if (!$this->isTaxEnabled()) {
            return false;
        }

        return get_ecommerce_setting('display_product_price_including_taxes', '0') == '1';
    }

    /**
     * @return array
     */
    public function getAvailableCountries(): array
    {
        try {
            $selectedCountries = json_decode(get_ecommerce_setting('available_countries'), true);
        } catch (Exception $exception) {
            $selectedCountries = [];
        }

        if (empty($selectedCountries)) {
            return Helper::countries();
        }

        $countries = [];

        foreach (Helper::countries() as $key => $item) {
            if (in_array($key, $selectedCountries)) {
                $countries[$key] = $item;
            }
        }

        return $countries;
    }
}
