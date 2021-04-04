<?php

namespace Platform\Setting\Http\Requests;

use Platform\Support\Http\Requests\Request;

class SendTestEmailRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
