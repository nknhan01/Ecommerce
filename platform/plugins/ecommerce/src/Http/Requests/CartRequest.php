<?php

namespace Platform\Ecommerce\Http\Requests;

use Platform\Support\Http\Requests\Request;

class CartRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'  => 'required|min:1|integer',
            'qty' => 'min:1|integer',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => __('Product ID is required'),
            'id.integer'  => __('Product ID must be a number'),
        ];
    }
}
