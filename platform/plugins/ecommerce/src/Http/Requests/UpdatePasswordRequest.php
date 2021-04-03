<?php

namespace Platform\Ecommerce\Http\Requests;

use Platform\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     */
    public function rules()
    {
        return [
            'old_password'          => 'required|min:6|max:60',
            'password'              => 'required|min:6|max:60',
            'password_confirmation' => 'same:password',
        ];
    }
}
