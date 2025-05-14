<?php

return [
    'email'      => 'Email không hợp lệ',
    'unique'     => ':Attribute này đã tồn tại',
    'regex'      => ':Attribute không đúng định dạng',
    'required'   => 'Trường :attribute là bắt buộc',
    'confirmed'  => ':Attribute xác nhận không khớp', 
    'same'       => ':Attribute không khớp',
    // 'same'    => ':attribute phải khớp với :other.',
    'url'        => 'Đường dẫn không hợp lệ',
    'max' => [
        // 'array'   => 'The :attribute field must not have more than :max items.',
        // 'file'    => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric'    => ':Attribute không được vượt quá :max',
        // 'string'  => 'The :attribute field must not be greater than :max characters.',
    ],
    'min' => [
        // 'array'   => 'The :attribute field must have at least :min items.',
        // 'file'    => 'The :attribute field must be at least :min kilobytes.',
        'numeric'    => ':Attribute phải lớn hơn :min',
        // 'string'  => 'The :attribute field must be at least :min characters.',
    ],
    // Doesn't work ?
    // 'attributes' => [
    //     'password' => 'Mật khẩu',
    //     'phone' => 'Số điện thoại',
    //     'email' => 'Email',
    // ],
];