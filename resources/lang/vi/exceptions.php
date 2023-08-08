<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in Exceptions thrown throughout the system.
    | Regardless where it is placed, a button can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'general' => [
        'unexpected_error'  => 'Một Lỗi Không Mong Muốn Đã Xảy Ra. :details.',
    ],

    'backend' => [
        'access' => [
            'roles' => [
                'already_exists'    => 'Vai Trò Đó Đã Tồn Tại. Vui Lòng Chọn Một Tên Khác.',
                'cant_delete_admin' => 'Bạn Không Thể Xóa Vai Trò Quản Trị Viên.',
                'create_error'      => 'Đã Xảy Ra Sự Cố Khi Tạo Vai Trò này. Vui Lòng Thử Lại.',
                'delete_error'      => 'Đã Xảy Ra Sự Cố Khi Xóa Vai Trò Này. Vui Lòng Thử Lại.',
                'has_users'         => 'Bạn Không Thể Xóa Vai Trò Với Những Người Dùng Được Liên Kết.',
                'needs_permission'  => 'Bạn Phải Chọn Ít Nhất Một Quyền Cho Vai Trò Này.',
                'not_found'         => 'Vai Trò Đó Không Tồn Tại.',
                'update_error'      => 'Đã Xảy Ra Sự Cố Khi Cập Nhật Vai Trò Này. Vui Lòng Thử Lại.',
            ],

            'users' => [
                'cant_deactivate_self'  => 'Bạn Không Thể Làm Điều Đó Với Chính Mình.',
                'cant_delete_admin'  => 'Bạn Không Thể Xóa QUản Trị Viên Cấp Cao.',
                'cant_delete_self'      => 'Bạn Không Thể Xóa Chính Mình.',
                'cant_delete_own_session' => 'Bạn Không Thể Xóa Phiên Của Chính Mình.',
                'cant_restore'          => 'Người Dùng Này Không Bị Xóa Nên Không Thể Khôi Phục Được.',
                'create_error'          => 'Đã Xảy ra Sự Cố Khi Tạo Người Dùng Này. Vui Lòng Thử Lại.',
                'delete_error'          => 'Đã Xảy Ra Sự Cố Khi Xóa Người Dùng Này. Vui Lòng Thử Lại.',
                'delete_first'          => 'Người Dùng Này Phải Bị Xóa Đầu Tiên Trước Khi Có Thể Bị Hủy Vĩnh Viễn.',
                'email_error'           => 'Địa Chỉ Email Đó Thuộc Về Một Người Dùng Khác.',
                'mark_error'            => 'Đã Xảy Ra Sự Cố Khi Cập Nhật Người Dùng Này. Vui Lòng Thử Lại.',
                'not_found'             => 'Người Dùng Đó Không Tồn Tại.',
                'restore_error'         => 'Đã Xảy Ra Sự Cố Khi Khôi Phục Người Dùng Này. Vui Lòng Thử Lại.',
                'role_needed_create'    => 'Bạn Phải Chọn Ít Nhất Một Vai Trò.',
                'role_needed'           => 'Bạn Phải Chọn Ít Nhất Một Vai Trò.',
                'session_wrong_driver'  => 'Trình Điều Khiển Phiên Của Bạn Phải Được Đặt Thành Cơ Sở Dữ Liệu Để Sử Dụng Tính Năng Này.',
                'update_error'          => 'Đã Xảy Ra Sự Cố Khi Cập Nhật Người Dùng Này. Vui Lòng Thử Lại.',
                'update_password_error' => 'Đã Xảy Ra Sự Cố Khi Thay Đổi Mật Khẩu Người Dùng Này. Vui Lòng Thử Lại.',
            ],
        ],
    ],

    'frontend' => [
        'auth' => [
            'confirmation' => [
                'already_confirmed' => 'Tài Khoản Của Bạn Đã Được Xác Nhận.',
                'confirm'           => 'Xác Nhận Tài Khoản Của Bạn!',
                'created_confirm'   => 'Tài Khoản Của Bạn Đã Được Tạo Thành Công. Chúng Tôi Đã Gửi Cho Bạn Một E-Mail Để Xác Nhận Tài Khoản Của Bạn.',
                'mismatch'          => 'Mã Xác Nhận Của Bạn Không Khớp.',
                'not_found'         => 'Mã Xác Nhận Đó Không Tồn Tại.',
                'resend'            => 'Tài Khoản Của Bạn Chưa Được Xác Nhận. Vui Lòng Nhấp Vào Liên Kết Xác Nhận Trong E-Mail Của Bạn, Hoặc <a href="'.route('frontend.auth.account.confirm.resend', ':user_id').'">Bấm Vào Đây</a> Để Gửi Lại E-Mail Xác Nhận.',
                'success'           => 'Tài Khoản Của Bạn Đã Được Xác Nhận Thành Công!',
                'resent'            => 'Một E-Mail Xác Nhận Mới Đã Được Gửi Đến Địa Chỉ Trong Hồ Sơ.',
            ],

            'deactivated' => 'Tài Khoản Của Bạn Đã Bị Vô Hiệu Hóa.',
            'email_taken' => 'Địa Chỉ E-Mail Đó Đã Được Sử Dụng.',

            'password' => [
                'change_mismatch' => 'Đó Không Phải Là Mật Khẩu Cũ Của Bạn.',
                'reset_problem' => 'Đã Xảy Ra Sự Cố Khi Đặt Lại Mật Khẩu Của Bạn. Vui Lòng Gửi Lại Email Đặt Lại Mật Khẩu.',
            ],

            'registration_disabled' => 'Đăng Ký Hiện Đã Đóng.',
        ],
        'addClass' => [
            'timelap_title' => 'Thời Gian Trùng Lặp!',
            'timelap_text' => 'Thời Gian Bắt Đầu Và Kết Thúc Sự Kiện Không Được Trùng Lặp',
            'overlap_title' => 'Xung Đột Tài Nguyên!',
            'overlap_text' => 'Bạn Muốn Làm Gì?',
            'overlap_button1' => 'Quay Lại chỉnh Sửa',
            'overlap_button2' => 'Chấp Nhận Xung Đột',
            'missingFields_title' => 'Thiếu Trường!',
            'missingFields_text' => 'Bạn Phải Hoàn Thành Tất Cả Các Trường Bắt Buộc',
            'initialMeetingRoom_title' => 'Phòng Họp Ban Đầu',
            'initialMeetingRoom_text' => 'Bạn Phải Chọn Một Phòng Họp Ban Đầu',
        ],
    ],
];
