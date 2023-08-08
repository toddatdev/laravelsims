<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Strings Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in strings throughout the system.
    | Regardless where it is placed, a string can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'users' => [
                'delete_user_confirm'  => 'Bạn có chắc chắn muốn xóa người dùng này vĩnh viễn không? Bất kỳ nơi nào trong ứng dụng có liên quan đến id của người dùng này rất có thể sẽ bị lỗi. Tiến hành với rủi ro đã thông báo. Việc này không thể được hoàn tác.',
                'if_confirmed_off'     => '(Nếu xác nhận là tắt)',
                'restore_user_confirm' => 'Khôi phục người dùng này về trạng thái ban đầu?',
            ],
        ],

        'dashboard' => [
            'title'   => 'Quản trị trang web',
            'welcome' => 'Chào mừng',
        ],

        'general' => [
            'all_rights_reserved' => 'Đã đăng ký Bản quyền.',
            'are_you_sure'        => 'Bạn có chắc chắn muốn làm điều này?',
            'boilerplate_link'    => 'Laravel SIMS 5.4',
            'continue'            => 'Tiếp tục',
            'member_since'        => 'Thành viên kể từ',
            'minutes'             => ' phút',
            'search_placeholder'  => 'Tìm kiếm...',
            'timeout'             => 'Bạn đã tự động bị đăng xuất cho lý do bảo mật vì bạn không có hoạt động nào trong ',

            'see_all' => [
                'messages'      => 'Xem tất cả tin nhắn',
                'notifications' => 'Xem tất cả',
                'tasks'         => 'Xem tất cả các nhiệm vụ',
            ],

            'status' => [
                'online'  => 'Trực tuyến',
                'offline' => 'Ngoại tuyến',
            ],

            'you_have' => [
                'messages'      => '{0} Bạn không có tin nhắn|{1} Bạn có 1 tin nhắn|[2,Inf] Bạn có :number tin nhắn',
                'notifications' => '{0} Bạn không có thông báo|{1} Bạn có 1 thông báo|[2,Inf] Bạn có :number thông báo',
                'tasks'         => '{0} Bạn không có nhiệm vụ|{1} Bạn có 1 nhiệm vụ|[2,Inf] Bạn có :number nhiệm vụ',
            ],
        ],

        'search' => [
            'empty'      => 'Vui lòng nhập một cụm từ tìm kiếm.',
            'incomplete' => 'Bạn phải viết tìm kiếm hợp lý của riêng bạn cho hệ thống này.',
            'title'      => 'Kết quả tìm kiếm',
            'results'    => 'Tìm kiếm kết quả cho :query',
        ],

        'welcome' => '<p>Đây là phần quản trị cho Hệ thống quản lý thông tin mô phỏng (SIMS). Chỉ quản trị viên chương trình mô phỏng mới có quyền truy cập. Người dùng có thể xem các trang quản trị này bằng cách <span style=font-weight:bold;>Xem quản trị trang web</span> quyền trong ít nhất một trong các vai trò SIMS, được chỉ định theo  <span style=font-weight:bold;>Tài khoản → Quản lý người dùng</span> trên menu bên trái tại đây. </p>',],

    'emails' => [
        'auth' => [
            'error'                   => 'Rất tiếc!',
            'greeting'                => 'Xin chào',
            'regards'                 => 'Trân trọng,',
            'trouble_clicking_button' => 'Nếu bạn gặp sự cố khi nhấp vào nút ":action_text", sao chép và dán URL bên dưới vào trình duyệt web của bạn:',
            'thank_you_for_using_app' => 'Cảm ơn bạn đã sử dụng ứng dụng của chúng tôi!',

            'password_reset_subject'    => 'Đặt lại mật khẩu',
            'password_cause_of_email'   => 'Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.',
            'password_if_not_requested' => 'Nếu bạn không yêu cầu đặt lại mật khẩu, bạn không cần thực hiện thêm hành động nào.',
            'reset_password'            => 'Nhấn vào đây để đặt lại mật khẩu của bạn',

            'click_to_confirm' => 'Bấm vào đây để xác nhận tài khoản của bạn:',
        ],
    ],

    'frontend' => [

        'no_building_info' => 'Không có thông tin có sẵn cho tòa nhà này. Liên hệ <a href=mailto:":siteEmail">:siteEmail</a> để được hỗ trợ.',
        'no_location_info' => 'Không có thông tin cho vị trí này. Liên hệ <a href=mailto:":siteEmail">:siteEmail</a> để được hỗ trợ.',

        'event-dashboard-login' => 'Nếu bạn liên kết với sự kiện này, hãy <a href="/login">login</a> để xem thông tin bổ sung.',
        'event-dashboard-waitlist' => 'Bạn đã yêu cầu đăng ký tham gia sự kiện này và hiện đang ở trong danh sách chờ. Hãy liên hệ <a href=mailto:":siteEmail">:siteEmail</a> để được hỗ trợ',

        'waitlist_request' => '<h5><a href="/courseInstance/events/event-dashboard/:eventId">:eventName</a></h5>
                                <p>Bạn đã yêu cầu đăng ký vào :createdAt và sẽ sớm nhận được phản hồi.
                                Nếu bạn không nhận được, vui lòng liên hệ <a href=mailto:":siteEmail">:siteEmail</a>.</p>',

        'self_parked' => '<h5>:eventName</h5>
                                <p>Bạn đã được đưa vào danh sách chờ.  Bạn sẽ được thông báo nếu bạn được chỉ định tham gia một sự kiện.
                                Hãy liên hệ <a href=mailto:":siteEmail">:siteEmail</a> với bất kỳ câu hỏi nào.</p>',

        'parked_from_event' => '<h5>:eventName</h5>
                                <p>Bạn đã được chuyển đến danh sách chờ.  Tại thời điểm này, bạn chưa được chỉ định vào một lớp học mới. Bạn sẽ được thông báo khi bạn được chỉ định lại.
                                Hãy liên hệ <a href=mailto:":siteEmail">:siteEmail</a> với bất kỳ câu hỏi nào.</p>',

        'test' => 'Kiểm tra',

        'tests' => [
            'based_on' => [
                'permission' => 'Dựa trên quyền - ',
                'role'       => 'Dựa trên vai trò - ',
            ],

            'js_injected_from_controller' => 'Javascript được đưa vào từ một bộ điều khiển',

            'using_blade_extensions' => 'Sử dụng tiện ích mở rộng Blade',

            'using_access_helper' => [
                'array_permissions'     => 'Sử dụng Trình trợ giúp truy cập với Mảng tên quyền hoặc ID mà người dùng sở hữu tất cả.',
                'array_permissions_not' => 'Sử dụng Trình trợ giúp truy cập với Mảng tên quyền hoặc ID mà người dùng không cần phải sở hữu tất cả.',
                'array_roles'           => 'Sử dụng Trình trợ giúp truy cập với Mảng tên vai trò hoặc ID mà người dùng sở hữu tất cả.',
                'array_roles_not'       => 'Sử dụng Trình trợ giúp Access với Mảng Tên vai trò hoặc ID trong đó người dùng không cần phải sở hữu tất cả.',
                'permission_id'         => 'Sử dụng Trình trợ giúp truy cập với ID cho phép',
                'permission_name'       => 'Sử dụng Trình trợ giúp truy cập với Tên cho phép',
                'role_id'               => 'Sử dụng Trình trợ giúp truy cập với vai trò ID ',
                'role_name'             => 'Sử dụng Trình trợ giúp truy cập với vai trò Tên ',
            ],

            'view_console_it_works'          => 'Xem bảng điều khiển, bạn sẽ thấy \'it works!\' đến từ FrontendController @ index',
            'you_can_see_because'            => 'Bạn có thể thấy điều này bởi vì bạn có vai trò \':role\'!',
            'you_can_see_because_permission' => 'Bạn có thể thấy điều này vì bạn có quyền của \':permission\'!',
        ],

        'user' => [
            'change_email_notice' => 'Nếu bạn thay đổi e-mail của mình, bạn sẽ đăng xuất cho đến khi bạn xác nhận địa chỉ e-mail mới của mình.',
            'email_changed_notice' => 'Bạn phải xác nhận địa chỉ e-mail mới của mình trước khi có thể đăng nhập lại.',
            'profile_updated'  => 'Hồ sơ được cập nhật thành công.',
            'password_updated' => 'Đã cập nhật mật khẩu thành công.',
        ],
        'event_request' => [
            'request_submitted' => 'Yêu cầu đăng ký của bạn đã được gửi.'
        ],
        'welcome_to' => 'Chào mừng đến :place',
        'email' => [
            'based_upon' => 'Điều này dựa trên',
            'site_email_template' => 'mẫu email trang web',
            'course_email_template' => 'mẫu email khóa học',
            'but_edited' => 'nhưng đã chỉnh sửa',
            'not_based_site_template' => 'Điều này không dựa trên bất kỳ mẫu email trang web nào.',
            'not_based_course_template' => 'Điều này không dựa trên bất kỳ mẫu email khóa học nào.',
        ],
        'event_user' => [
            'parked' => 'Đã đăng ký trước đây, xem lịch sử.'
        ],
    ],
];
