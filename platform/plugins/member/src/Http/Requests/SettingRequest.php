<?php

namespace Platform\Member\Http\Requests;

use Platform\Support\Http\Requests\Request;

class SettingRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:120',
            'last_name'  => 'required|max:120',
            'phone'      => 'max:20|sometimes',
            'dob'        => 'max:20|sometimes',
        ];
    }
}
