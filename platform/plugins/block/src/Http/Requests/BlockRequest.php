<?php

namespace Platform\Block\Http\Requests;

use Platform\Base\Enums\BaseStatusEnum;
use Platform\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BlockRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required|max:120',
            'alias'  => 'required|max:120',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
