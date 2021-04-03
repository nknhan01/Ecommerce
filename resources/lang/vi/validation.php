<?php

return [
    'accepted' => 'Trường :attribute phải được chấp nhận.',
    'active_url' => 'Trường :attribute không phải là một URL hợp lệ.',
    'after' => 'Trường :attribute phải là một ngày sau ngày :date.',
    'alpha' => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => 'Trường :attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num' => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
    'array' => 'Kiểu dữ liệu của trường :attribute phải là dạng mảng.',
    'before' => 'Trường :attribute phải là một ngày trước ngày :date.',
    'between' =>
        [
            'numeric' => 'Trường :attribute phải nằm trong khoảng :min - :max.',
            'file' => 'Dung lượng tập tin trong trường :attribute phải từ :min - :max kB.',
            'string' => 'Trường :attribute phải từ :min - :max ký tự.',
            'array' => 'Trường :attribute phải có từ :min - :max phần tử.',
        ],
    'boolean' => 'Trường :attribute phải là true hoặc false.',
    'confirmed' => 'Giá trị xác nhận trong trường :attribute không khớp.',
    'date' => 'Trường :attribute không phải là định dạng của ngày-tháng.',
    'date_format' => 'Trường :attribute không giống với định dạng :format.',
    'different' => 'Trường :attribute và :other phải khác nhau.',
    'digits' => 'Độ dài của trường :attribute phải gồm :digits chữ số.',
    'digits_between' => 'Độ dài của trường :attribute phải nằm trong khoảng :min and :max chữ số.',
    'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'exists' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'file' => 'Trường :attribute phải là một tập tin.',
    'image' => 'Các tập tin trong trường :attribute phải là định dạng hình ảnh.',
    'in' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'integer' => 'Trường :attribute phải là một số nguyên.',
    'ip' => 'Trường :attribute phải là một địa chỉa IP.',
    'max' =>
        [
            'numeric' => 'Trường :attribute không được lớn hơn :max.',
            'file' => 'Dung lượng tập tin trong trường :attribute không được lớn hơn :max kB.',
            'string' => 'Trường :attribute không được lớn hơn :max ký tự.',
            'array' => 'Trường :attribute không được lớn hơn :max phần tử.',
        ],
    'mimes' => 'Trường :attribute phải là một tập tin có định dạng: :values.',
    'min' =>
        [
            'numeric' => 'Trường :attribute phải tối thiểu là :min.',
            'file' => 'Dung lượng tập tin trong trường :attribute phải tối thiểu :min kB.',
            'string' => 'Trường :attribute phải có tối thiểu :min ký tự.',
            'array' => 'Trường :attribute phải có tối thiểu :min phần tử.',
        ],
    'not_in' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'numeric' => 'Trường :attribute phải là một số.',
    'regex' => 'Định dạng trường :attribute không hợp lệ.',
    'required' => 'Trường :attribute không được bỏ trống.',
    'required_if' => 'Trường :attribute không được bỏ trống khi trường :other là :value.',
    'required_with' => 'Trường :attribute không được bỏ trống khi trường :values có giá trị.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'Trường :attribute không được bỏ trống khi trường :values không có giá trị.',
    'required_without_all' => 'Trường :attribute không được bỏ trống khi tất cả :values không có giá trị.',
    'same' => 'Trường :attribute và :other phải giống nhau.',
    'size' =>
        [
            'numeric' => 'Trường :attribute phải bằng :size.',
            'file' => 'Dung lượng tập tin trong trường :attribute phải bằng :size kB.',
            'string' => 'Trường :attribute phải chứa :size ký tự.',
            'array' => 'Trường :attribute phải chứa :size phần tử.',
        ],
    'timezone' => 'Trường :attribute phải là một múi giờ hợp lệ.',
    'unique' => 'Trường :attribute đã có trong CSDL.',
    'url' => 'Trường :attribute không giống với định dạng một URL.',
    'uploaded' => 'Không thể tải lên, hãy thử lại.',
    'custom' =>
        [
            'email' =>
                [
                    'email' => 'Địa chỉ email không hợp lệ',
                    'required' => 'Email không được để trống!',
                    'unique' => 'Email đã được sử dụng, vui lòng chọn email khác!',
                ],
            'password' =>
                [
                    'min' => 'Mật khẩu phải ít nhất :min kí tự.',
                    'required' => 'Mật khẩu không được để trống!',
                ],
            'repassword' =>
                [
                    'same' => 'Mật khẩu và xác nhận mật khẩu không trùng khớp',
                ],
            'username' =>
                [
                    'min' => 'Tên phải ít nhất 6 kí tự',
                    'required' => 'Tên đăng nhập không được để trống!',
                    'unique' => 'Tên này đã được sử dụng, vui lòng chọn tên khác!',
                ],
        ],
    'attributes' => 'Thuộc tính',
    'after_or_equal' => 'Thuộc tính :attribute phải là ngày lớn hơn hoặc bằng :date',
    'before_or_equal' => 'Trường :attribute phải là ngày trước hoặc bằng ngày :date',
];
