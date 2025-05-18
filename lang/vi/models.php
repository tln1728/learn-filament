<?php

return [
    'user' => [
        'name'              => 'Tên',
        'email'             => 'Email',
        'email_verified_at' => 'Ngày xác nhận email',
        'password'          => 'Mật khẩu',
        'phone'             => 'Số điện thoại',
        // 'hotel_id'          => 'Khách sạn',
        'avatar'            => 'Ảnh đại diện',
        'birth'             => 'Ngày sinh',
        'created_at'        => 'Ngày tạo',
        'updated_at'        => 'Ngày cập nhật gần nhất',
    ],

    'hotel' => [
        'name'         => 'Tên khách sạn',
        'description'  => 'Mô tả ngắn',
        'address'      => 'Địa chỉ',
        'city'         => 'Thành phố',
        'country'      => 'Quốc gia',
        'zip_code'     => 'Mã bưu điện',        
        'phone'        => 'Liên hệ',
        'email'        => 'Email',
        'website'      => 'Trang web',
        'created_at'   => 'Ngày thành lập', // ?
        'updated_at'   => 'Cập nhật gần nhất',
    ],

    // models.hotel.basic_information
    // models.hotel.location
    // models.hotel.contact_information

    'roomtype' => [
        'hotel_id'      => 'Khách sạn',
        'name'          => 'Tên loại phòng',
        'description'   => 'Mô tả',
        'max_occupancy' => 'Sức chứa tối đa',
        'base_price'    => 'Giá cơ bản',
        'created_at'    => 'Ngày tạo',
        'updated_at'    => 'Cập nhật gần nhất',
        // Relationships
        'rooms_count'   => 'Số phòng',
    ],

    'room' => [
        'hotel_id'      => 'Khách sạn',
        'room_type_id'  => 'Loại phòng',
        'room_number'   => 'Số phòng',
        'is_available'  => 'Trạng thái',
        'created_at'    => 'Ngày tạo',
        'updated_at'    => 'Cập nhật gần nhất',
    ],

    // 'booking' => 'Đặt phòng',

    // 'payment' => 'Thanh toán',

    // 'review' => 'Đánh giá',

    // 'roomrate' => 'rôm rết',
];