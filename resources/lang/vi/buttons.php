<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Buttons Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in buttons throughout the system.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'users' => [
                'activate'           => 'Hoạt Hóa',
                'change_password'    => 'Đổi Mật Khẩu',
                'clear_session'      => 'Xóa Phiên Hoạt Động',
                'deactivate'         => 'Vô Hiệu Hóa',
                'delete_permanently' => 'Xóa Vĩnh Viễn',
                'login_as'           => 'Đăng Nhập Với Vai Trò :user',
                'resend_email'       => 'Gửi Lại Email Xác Nhận',
                'restore_user'       => 'Khôi Phục Người Dùng',
                'edit_profile'       => 'Chỉnh Sửa Hồ Sơ Người Dùng',
            ],
        ],
        'buildings' => [
                'activate'           => 'Hoạt Động',
                'retire'             => 'Không Còn Hoạt Động/ Sử Dụng',
        ],
        'locations' => [
                'activate'           => 'Hoạt Động',
                'retire'             => 'Không Còn Hoạt Động/ Sử Dụng',
                'remove_scheduler_all' => 'Xóa:name Khỏi Lịch Tất Cả Địa Điểm',
        ],
        'courses' => [
            'categories'             => 'Các Danh Mục',
            'options'                => 'Tùy Chọn',
            'editGroup'              => 'Chỉnh Sửa Nhóm',
            'addGroup'               => 'Thêm Nhóm Mới',
            'deleteGroup'            => 'Xóa Nhóm',
            'addCategory'            => 'Thêm Danh Mục',
            'deleteCategory'         => 'Xóa Danh Mục',
            'editCategory'           => 'Chỉnh Sửa Danh Mục',
            'templates'              => 'Mẫu Khóa Học',
        ],
        'resources' => [
            'allCategory'            => 'Tất Cả Các Danh Mục',
            'addCategory'            => 'Thêm Danh Mục',
            'editCategory'           => 'Chỉnh Sửa Danh Mục',
            'deleteCategory'         => 'Xóa Danh Mục',
            'disabledDeleteCategory' => 'Danh Mục Đang Được Sử Dụng Và Không Thể Xóa',
            'addSubCategory'         => 'Thêm Danh Mục Phụ',
            'editSubCategory'        => 'Chỉnh Sửa Danh Mục Phụ',
            'deleteSubCategory'      => 'Xóa Danh Mục Phụ',
            'disabledDeleteSubCategory'=> 'Danh Mục Phụ Đang Được Sử Dụng Và Không Thể Xóa',
            'equipment'              => 'Trang Thiết Bị',
            'rooms'                  => 'Phòng',
            'personnel'              => 'Nhân Sự',

        ],
        'user-profile-questions' => [
            'firstLevelPlus'           => 'firstLevelPlus',

        ],
        'siteEmails' => [
            'activate'           => 'Hoạt động',
            'delete'             => 'Xóa',
        ],
    ],

    'emails' => [
        'auth' => [
            'confirm_account' => 'Xác Nhận Tài Khoản',
            'reset_password'  => 'Đặt Lại Mật Khẩu',
        ],
    ],

    'general' => [
        'cancel'    => 'Hủy',
        'continue'  => 'Tiếp Tục',
        'deactivate' => 'Vô Hiệu Hóa',
        'activate'  => 'Hoạt Động',
        'image'     => 'Tải Lên Hình Ảnh',
        'image_new' => 'Tải Lên Hình Ảnh Mới',
        'options'   => 'Tùy Chọn',
        'add_row'   => 'Thêm dòng',
        'export'    => 'Xuất',
        'import'    => 'Nhập',
        'add_user'  => 'Thêm Người Dùng',
        'retire'    => 'Không Còn Hoạt Động/ Sử Dụng',
        'filter'    => 'Bộ Lọc',
        'email'     => 'Email',
        'send_now'  => 'Gửi Thủ Công',
        'enroll'    => 'Ghi Danh',

        'crud' => [
            'create' => 'Nộp',
            'delete' => 'Xóa',
            'edit'   => 'Chỉnh sửa',
            'save'   => 'Lưu',
            'update' => 'Cập nhật',
            'view'   => 'Xem',
            'add'    => 'Thêm',
            'duplicate'  => 'Bản Sao',
            'copy'   => 'Sao Chép',
            'move' => 'Di Chuyển',
            'waitlist' => 'Danh Sách Chờ',
            'comments' => 'Bình Luận'
        ],

        //for location schedulers
        'schedulers' => 'Người Lên Lịch',

        'add_request_to_group'   => 'Thêm một yêu cầu khác vào nhóm này',
        'copy_request'           => 'Sao chép yêu cầu này sang một ngày khác',

        'save' => 'Lưu',
        'view' => 'Xem',
        'register'  => 'Đăng ký',
        'my_courses' => 'Khóa Học Của Tôi',
    ],

    'course_templates' => [
        'create_template' => 'Tạo Mẫu Khóa Học',
    ],
    'calendar' => [
        'go_to_day'     => 'Đi Đến Ngày Này Trên Lịch',
        'display_notes' => 'Hiển Thị Ghi Chú',
        'hide_notes'    => 'Ẩn Ghi Chú'
    ],

    'event' => [
        'add_people'            => 'Thêm Người Vào Sự Kiện',
        'edit'                  => 'Chỉnh Sửa Sự Kiện',
        'recurrence_group'      => 'Nhóm lặp lại',
        'duplicate'             => 'Sự Kiện Trùng Lặp',
        'anotherEvent'          => 'Thêm Sự Kiện Khác Vào Nhóm',
        'newEventSameCourse'    => 'Thêm Một Sự Kiện Mới Vào Cùng Một Khóa Học',
        'delete'                => 'Xóa Sự Kiện',
        'dashboard'             => 'Trang Chủ Sự Kiện',
        'view_history'          => 'Xem Lịch Sử',
        'park'                  => 'Chỗ Đỗ Xe',
        'restore'               => 'Khôi Phục Sự Kiện'
    ],

    'curriculum' => [
        'publish'            => 'Công Khai',
        'add_module'         => 'Thêm Chương Trình Học',
    ],

];
