<?php

namespace Platform\Theme\Http\Requests;

use Platform\Support\Http\Requests\Request;

class LoginRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        return $rule;
    }

    /**
     * Get the message validate.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ];
    }
    
}