<?php

namespace Platform\PageBuilder\Http\Requests;

use Platform\Support\Http\Requests\Request;

class PageBuilderRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
        ];
    }
}
