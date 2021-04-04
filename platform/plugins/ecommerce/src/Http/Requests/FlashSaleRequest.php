<?php

namespace Platform\Ecommerce\Http\Requests;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FlashSaleRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                      => 'required',
            'end_date'                  => 'required',
            'products_extra.*.price'    => 'required|numeric',
            'products_extra.*.quantity' => 'required|numeric',
            'status'                    => Rule::in(BaseStatusEnum::values()),
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'products_extra.*.price'    => trans('plugins/ecommerce::products.price'),
            'products_extra.*.quantity' => trans('plugins/ecommerce::products.quantity'),
        ];
    }
}
