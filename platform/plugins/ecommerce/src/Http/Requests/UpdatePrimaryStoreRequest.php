<?php

namespace Platform\Ecommerce\Http\Requests;

use Platform\Support\Http\Requests\Request;

class UpdatePrimaryStoreRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'primary_store_id' => 'required|numeric',
        ];
    }
}
