<?php

namespace Platform\Member\Http\Requests;

class PostRequest extends \Platform\Blog\Http\Requests\PostRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules() + ['image_input' => 'image|mimes:jpg,jpeg,png'];
    }
}
