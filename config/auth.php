<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Thiết lập mặc định cho authentication, sử dụng `jwt` cho API.
    |
    */

    'defaults' => [
    'guard' => 'web',  // Chuyển từ 'api' sang 'web'
    'passwords' => 'users',
],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Xác thực bằng session cho web và JWT cho API.
    |
    */

   'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Lấy thông tin người dùng từ model User (Eloquent).
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Thiết lập reset mật khẩu cho người dùng.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60, // Token reset có hiệu lực trong 60 phút
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Thời gian timeout của xác nhận mật khẩu (3 giờ).
    |
    */

    'password_timeout' => 10800,

];
