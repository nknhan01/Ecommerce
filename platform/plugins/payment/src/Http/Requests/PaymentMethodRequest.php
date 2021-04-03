<?php

namespace Platform\Payment\Http\Requests;

use Platform\Support\Http\Requests\Request;

class PaymentMethodRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|max:120',
        ];
    }
}
