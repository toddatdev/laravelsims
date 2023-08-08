<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */
    'frontend' => [
        'scheduling' => [
            'add-request'   => 'Tạo Yêu Cầu Mới',
            'view-all'     => 'Xem Tất Cả Các Yêu Cầu',
            'view-pending' => 'Xem Các Yêu Cầu Đang Chờ Xử Lý',
            'view-approved' => 'Xem Các Yêu Cầu Đã Được Chấp Thuận',
            'view-denied'   => 'Xem Các Yêu Cầu Đã Bị Từ Chối',
            'tasks'        => 'Nội Dung Yêu Cầu Đang Chờ Xử lý',
            'recurrence_group' => 'Nhóm Lặp Lại',
        ],

        'event' => [
            'assign'   => 'Chỉ Định Người Dùng/Vai Trò Của Sự Kiện',
        ],
    ],

    'backend' => [
        'access' => [
            'title' => 'Tài Khoản',

            'roles' => [
                'all'           => 'Tất Cả Các Vai Trò',
                'create'        => 'Tạo Vai Trò',
                'edit'          => 'Chỉnh Sửa Vai Trò',
                'management'    => 'Quản Lý Vai Trò',
                'main'          => 'Những Vai Trò',
                'site_roles'    => 'Những Vai Trò Trang Web',
                'course_roles'  => 'Những Vai Trò Khóa Học',
                'event_roles'   => 'Những Vai Trò Sự Kiện',
            ],

            'users' => [
                'all'             => 'Những Người Dùng Đang Hoạt Động',
                'change-password' => 'Đổi Mật Khẩu',
                'create'          => 'Tạo Người Dùng',
                'deactivated'     => 'Những Người Dùng Bị Vô Hiệu Hóa',
                'deleted'         => 'Những Người Dùng Đã Bị Xóa',
                'edit'            => 'Biên Tập Viên',
                'main'            => 'Những Người Dùng',
                'view'            => 'Xem Người Dùng',
            ],
        ],

        //      This section added to the bootstrap template for SIMS Laravel

        'user-profile-questions' => [
            'title' => 'Những Câu Hỏi Về Hồ Sơ Người Dùng',
            'view-all' => 'Xem Tất Cả Các Câu Hỏi Về Hồ Sơ',
            'create' => 'Tạo Câu Hỏi Mới Của Hồ Sơ',
            'edit' => 'Chỉnh Sửa Câu Hỏi Của Hồ Sơ',
            'questions' => 'Những Nhiệm Vụ Các Câu Hỏi',
            'update'  => 'Cập Nhật Câu Hỏi Của Hồ Sơ',
            'active'  => 'Xem Tất Cả Các Câu Hỏi Đang Sử Dụng',
            'retired' => 'Xem Tất Cả Các Câu Hỏi Không Còn Sử Dụng',
            'delete'  => 'Xác Nhận Xóa Câu Hỏi',
            'all-active' => 'Tất Cả Các Câu Hỏi Của Hồ Sơ Đang Sử Dụng',
            'all-retired' => 'Tất Cả Các Câu Hỏi Của Hồ Sơ Không Còn Sử Dụng',
        ],


        'site' => [
            'title' => 'Các Trang Web',
            'view-all' => 'Xem Tất Cả Các Trang Web',
            'create' => 'Tạo Một Trang Web Mới',
            'edit' => 'Chỉnh Sửa Trang Web',
            'roles' => 'Các Vai Trò Của Trang Web',
            'assign' => 'Chỉ Định Người Dùng/Vai Trò Của Trang Ceb',
            'site' => 'Trang Web',
            'email' => 'Email',
            'options' => 'Các Tùy Chọn',
        ],

        'course' => [
            'title'                 => 'Các Khóa Học',
            'view-all'              => 'Xem Tất Cả Các Khóa Học',
            'view-active'           => 'Xem Các Khóa Học Đang Hoạt Động',
            'view-inactive'         => 'Xem Các Khóa Học Không Còn Hoạt Động',
            'view-course'           => 'Xem Khóa Học',
            'create'                => 'Tạo Một Khóa Học Mới',
            'focus'                 => 'Quay Lại Khóa Học',
            'edit'                  => 'Chỉnh Sửa Khóa Học',
            'upload'                => 'Tải Lên Hình Ảnh Danh Mục',
            'image_current'         => 'Hình Ảnh Danh Mục Hiện Tại',
            'image_new'             => 'Hình Ảnh Danh Mục Mới',
            'image_remove'          => 'Xóa Hình Ảnh Danh Mục',
            'tasks'                 => 'Các Nhiệm Vụ Của Khóa Học',
            'roles'                 => 'Các Vai Trò Của Khóa Học',
            'assign'                => 'Chỉ Định Người Dùng Vào Vai Trò Trong Khóa Học',
            'curriculum'            => 'Chương Trình Giáo Dục',
            'edit_page'             => 'Chỉnh Sửa Trang',
            'edit_video'             => 'Chỉnh Sửa Video',
            'courses'               => 'Các Khóa Học',
            'my_courses'            => 'Các Khóa Học Của Tôi',
            'preview_page'          => 'Preview page',
            'show'                  => 'Show',
        ],

        'courseCategoryGroup'   => [
            'title'             => 'Các Nhớm Danh Mục Khóa Học',
            'create'            => 'Tạo Nhóm Danh Mục Khóa Học Mới',
            'edit'              => 'Chỉnh Sửa Nhóm Danh Mục Khóa Học',
        ],

        'courseCategory'    => [
            'title'         => 'Các Danh Mục Khóa Học',
            'create'        => 'Tạo Danh Mục Khóa Học Mới',
            'edit'          => 'Chỉnh Sửa Danh Mục Khóa Học',
        ],

        'courseOptions' => [
            'title'     => 'Các Tùy Chọn Khóa Học',
            'create'    => 'Tạo Tùy Chọn Khóa Học Mới',
            'edit'      => 'Chỉnh Sửa Tùy Chọn Khóa Học',
        ],

        'courseTemplates' => [
            'title'     => 'Mẫu Khóa Học',
            'create'    => 'Tạo Mẫu Mới',
            'edit'      => 'Chỉnh Sửa Mẫu',
            'tasks'     => 'Nhiệm Vụ Của Mẫu',
        ],

        'courseCurriculum' => [
            'title'                  => 'Chương Trình Giảng Dạy',
            'edit-course-curriculum' => 'Chỉnh Sửa Chương Trình Giảng Dạy',
        ],

        'resource' => [
            'title'         => 'Tài Nguyên',
            'manage'        => 'Quản Lý Tài Nguyên',
            'create'        => 'Tạo Tài Nguyên Mới',
            'edit'          => 'Chỉnh Sửa Tài Nguyên',
            'delete'        => 'Xóa Tài Nguyên',
            'view-all'      => 'Xem Tất Cả Tải Nguyên',
            'view-active'   => 'Xem Tài Nguyên Đang Hoạt Động',
            'view-inactive' => 'Xem Tài Nguyên Không Còn Hoạt Động',
            'tasks'         => 'Nhiệm Vụ Của Tài Nguyên',
        ],

        'resourceCategory'  => [
            'title'         => 'Các Danh Mục Tài Nguyên',
            'manage'        => 'Quản Lý Danh Mục',
            'create'        => 'Tạo Danh Mục Tài Nguyên Mới',
            'edit'          => 'Chỉnh Sửa Danh Mục',
            'delete'        => 'Xóa Danh Mục',
            'index'         => 'Xem Các Danh Mục Chính Và Các Danh Mục Phụ',
            'tasks'         => 'Các Nhiệm Vụ Của Danh Mục Tài Nguyên',
        ],

        'resourceSubCategory'  => [
            'title'         => 'Manh Mục Phụ Tài Nguyên',
            'create'        => 'Tạo Danh Mục Phụ Tài Nguyên Mới',
            'edit'          => 'Chỉnh Sửa Danh Mục Phụ Tài Nguyên',
            'delete'        => 'Xóa Danh Mục Phụ Tài Nguyên',
        ],


        'building' => [
            'title'        => 'Những Cơ Sở Học Tập',
            'view-all'     => 'Xem Tất Cả Cơ Sở Học Tập',
            'view-active'  => 'Xem Tất Cả Cơ Sở Học Tập Đang Hoạt Động',
            'view-retired' => 'Xem Tất Cả Cơ Sở Học Tập Không Còn Hoạt Động',
            'create'       => 'Xây Dựng Cơ Sở Học Tập Mới',
            'edit'         => 'Chỉnh Sửa Cơ Sở Học Tập',
            'tasks'        => 'Nhiệm Vụ Của Cơ Sở Học Tập',
        ],

        'location' => [
            'title'        => 'Những Địa Điểm',
            'view-all'     => 'Xem Tất Cả Các Địa Điểm',
            'view-active'  => 'Xem Các Địa Điểm Đang Hoạt Động',
            'view-retired' => 'Xem Các Địa Điểm Không Còn Hoạt Động',
            'create'       => 'Tạo Một Địa Điểm Mới',
            'edit'         => 'Chỉnh Sửa Địa Điểm',
            'tasks'        => 'Những Nhiệm Vụ Của Địa Điểm',
            'schedulers'   => 'Những Người Lên Lịch',
        ],
        
        'siteEmails' => [
            'title'        => 'Địa Chỉ Email',
            'create'       => 'Đang Tạo Email',
            'create-site'  => 'Tạo Địa Chỉ Email Mới',
            'create-course'=> 'Tạo Email Khóa Học Mới',
            'create-event' => 'Tạo Email Sự Kiện Mới',
            'edit-site'    => 'Chỉnh Sửa Địa Chỉ Email',
            'edit-course'  => 'Chỉnh Sửa Email Khóa Học',
            'edit-event'   => 'Chỉnh Sửa Email Sự Kiện',
            'tasks'        => 'Những Nhiệm Vụ Email',
            'view-item'    => 'Email',
            'site'         => 'Địa Chỉ Email' ,
            'course'       => 'Email Khóa Học',
            'event'        => 'Email Sự Kiện',
            'btn'          => 'Tạo Email'

        ],

        'courseEmails' => [
            'title'        => 'Email Khóa Học Cho',
            'create'       => 'Tạo Email Khóa Học Mới Cho ',
            'create-c'     => 'Tạo Email Khóa Học Mới',
            'create-e'     => 'Tạo Email Sự Kiện Mới',
            'edit'         => 'Chỉnh Sửa Các Email Cho',
            'tasks'        => 'Những Nhiệm Vụ Của Email',
            'view-item'    => 'Email',

        ],

        'eventEmails' => [
            'title'        => 'Những Email Sự Kiện',
            'create'       => 'Tạo Email Mới',
            'edit'         => 'Chỉnh Sửa Email',
            'tasks'        => 'Những Nhiệm Vụ Email',
            'view-item'    => 'Email',

        ],


//      End of SIMS Laravel Section

        'log-viewer' => [
            'main'      => 'Trình Xem Nhật Ký',
            'dashboard' => 'Bảng Điều Khiển',
            'logs'      => 'Nhật Ký',
        ],

        'sidebar' => [
            'dashboard' => 'Hành Chính', // I changed this. SIMS30-160 -jl 2019-07-17 14:27
            'general'   => 'Chung',
            'system'    => 'Hệ Thống',
        ],
    ],

    'language-picker' => [
        'language' => 'Ngôn Ngữ',
        /*
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'ar'    => 'Tiếng Ả Rập',
            'zh'    => 'Tiếng Trung Giản Thể',
            'zh-TW' => 'Tiếng Trung Phồn Thể',
            'da'    => 'Tiếng Đan Mạch',
            'de'    => 'Tiếng Đức',
            'el'    => 'Tiếng Hy Lạp',
            'en'    => 'Tiếng Anh',
            'es'    => 'Tiếng Tây Ban Nha',
            'fr'    => 'Tiếng Pháp',
            'id'    => 'Tiếng Indonesia',
            'it'    => 'Tiếng Ý',
            'ja'    => 'Tiếng Nhật',
            'nl'    => 'Tiếng Hà Lan',
            'pt_BR' => 'Tiếng Bồ Đào Nha Của Người Brazil',
            'ru'    => 'Tiếng Nga',
            'sv'    => 'Tiếng Thụy Điển',
            'th'    => 'Tiếng Thái Lan',
            'vi'    => 'Vietnamese',

        ],
    ],
];
