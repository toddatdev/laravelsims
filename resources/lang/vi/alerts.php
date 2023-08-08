<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */
    'general' => [
        'confirm_delete' => 'Xác nhận xóa',
        'confirm_delete_content' => 'Bạn có chắc chắn muốn xóa không',
        'confirm_restore' => 'Xác nhận khôi phục',
    ],

    'backend' => [
        'roles' => [
            'created'       => 'Vai trò đã được tạo thành công.',
            'deleted'       => 'Vai trò đã được xóa thành công.',
            'updated'       => 'Vai trò đã được cập nhật thành công.',
            'delete_wall'   => 'Bạn có chắc chắn muốn xóa vai trò này không?',
        ],

        'users' => [
            'confirmation_email'  => 'Một e-mail xác nhận mới đã được gửi đến địa chỉ trong hồ sơ.',
            'created'             => 'Người dùng đã được tạo thành công.',
            'deleted'             => 'Người dùng đã được xóa thành công.',
            'deleted_permanently' => 'Người dùng đã bị xóa vĩnh viễn.',
            'restored'            => 'Người dùng đã được khôi phục thành công.',
            'session_cleared'      => "Phiên của người dùng đã được xóa thành công.",
            'updated'             => 'Người dùng đã được cập nhật thành công.',
            'updated_password'    => "Mật khẩu của người dùng đã được cập nhật thành công.",
            'need_scheduler_permission' => 'Người dùng này không có quyền tạo lịch. Họ cần có quyền "Quản lý tạo lịch".',
        ],

        'resources' => [
            'created' => '<b>:ResourceName</b> đã được tạo thành công. <b><a href="/resources/duplicate/:ResourceID">Nhân đôi tài nguyên này</a></b>.',
            'deleted' => 'Tài nguyên đã được xóa thành công.',
            'updated' => 'Tài nguyên đã được cập nhật thành công.',
            'activated' => 'Tài nguyên đã được kích hoạt thành công.',
            'deactivated' => 'Tài nguyên đã được vô hiệu hóa thành công.',
        ],

        'resourcecategory' => [
            'created' => 'Danh mục tài nguyên đã được tạo thành công.',
            'updated' => 'Danh mục tài nguyên đã được cập nhật thành công.',
            'delete'  => 'Bạn có chắc chắn muốn xóa :categoryAbbrv danh mục?',
        ],

        'resourcesubcategory' => [
            'created' => 'Danh mục con tài nguyên đã được tạo thành công.',
            'updated' => 'Danh mục con tài nguyên đã được cập nhật thành công.',
            'delete'  => 'Bạn có chắc chắn muốn xóa :subcategoryAbbrv danh mục con?',

        ],

        'user-profile' => [
            'deleted' => 'Câu hỏi đã được xóa thành công.',
            'retired' => 'Câu hỏi đã được gỡ bỏ thành công.',
            'activated' => 'Câu hỏi đã được kích hoạt thành công.',
            'canNotBeDeleted' => 'Không thể xóa câu hỏi',
        ],

        'scheduling' => [
            'created' => 'Lớp học đã được tạo thành công.',
            'schedule_request_created' => 'yêu cầu thời khóa biểu được tạo thành công.',
        ],

        'templates' => [
            'created' => ' biểu mẫu đã được tạo thành công.',
            'edited' => ' biểu mẫu đã được chỉnh sửa thành công.',
            'delete_template_start' => 'Bạn có chắc chắn muốn xóa ',
            'delete_template_end' => ' biểu mẫu?',
            'delete_success' => ' đã xóa thành công.',
        ],
        'courseusers' => [
            'created' => 'Đã thêm thành công người dùng vào khóa học.',
            'deleted' => 'Đã xóa thành công người dùng khỏi khóa học.',
            'deletePart1' => 'Bạn có chắc chắn muốn xóa ',
            'deletePart2' => ' từ :course ',
            'deletePart3' => ' vai trò?',
            'required' => 'Vui lòng điền vào tất cả các trường bắt buộc.',
            'access' => 'Bạn không có quyền truy cập vào khóa học đó.',
            'invalid' => 'Đó không phải là một khóa học hợp lệ.',
        ],

        'siteusers' => [
            'created' => 'Đã thêm thành công người dùng vào vai trò trang web.',
            'deleted' => 'Đã xóa thành công người dùng khỏi vai trò trang web.',
            'delete' => 'Bạn có chắc chắn muốn xóa người dùng này khỏi vai trò trang web không?',
            'required' => 'Vui lòng điền vào tất cả các trường bắt buộc.',
        ],

        'options' => [
            'update' => 'Đã cập nhật tùy chọn trang web.',
        ],

        'createEvent' => [
            'conflict' => 'Nếu bạn đồng ý với những xung đột này, hãy nhấp vào OK và gửi lại.',
        ],
        'courseCurriculum' => [
            'delete_module' => 'Bạn có chắc chắn muốn xóa vĩnh viễn không ',
            'cannot_delete_module_with_content' => 'Bạn không thể xóa một mô-đun có nội dung trong đó.',
            'there_is_no_modules_start' => 'Không có ',
            'there_is_no_modules_end' => ' Nội dung. Nhấp vào Thêm mô-đun để bắt đầu.'
        ]

    ],

    'frontend' => [
        'scheduling' => [
            'created' => ' đã được tạo thành công.',
            'recurrence_created' => 'Nhiều sự kiện đã được tạo thành công.',
            'edited' => 'Sự kiện này đã được chỉnh sửa thành công.',
            'deleted' => ':eventName đã được xóa thành công.',
            'recurrence_deleted' => ':courseName on :eventDates đã được xóa thành công.',
            'restored' => '<a href="/courseInstance/events/event-dashboard/:eventId">:eventName trên :eventDate</a> đã được khôi phục thành công.',
            'confirm_delete_text' => 'Bạn có chắc chắn muốn xóa :Event?',
            'confirm_restore_text' => 'Bạn có chắc chắn muốn khôi phục không',
            'schedule_request_created' => 'yêu cầu lịch trình được tạo thành công.',
            'no_schedulers' => 'KHÔNG CÓ LỊCH TRÌNH NÀO ĐƯỢC ĐĂNG KÝ VÀO VỊ TRÍ NÀY',
            'no_location_access' => 'Bạn chưa được chỉ định là người tạo lịch cho bất kỳ vị trí nào. Xin vui lòng liên hệ ',
            'no_event_location_access' => 'Bạn chưa được chỉ định là người tạo lịch cho vị trí của sự kiện này. Xin vui lòng liên hệ ',
            'return_to_pending' => 'Quay lại yêu cầu đang chờ xử lý.',
            'delete_recurrence1' => 'Sự kiện này đã được thêm vào như một phần của nhóm các ngày lặp lại: ',
            'delete_recurrence2' => 'Bạn chỉ muồn xóa phần này ',
            'delete_recurrence3' => ' hay tất cả các sự kiện trong nhóm?'
        ],

        'eventusers' => [
            'created' => ':UserName đã thêm thành công với tên :RoleName đến :EventName.',
            'deleted' => ':UserName loại bỏ thành công khỏi :eventOrWaitlist.',
            'deleteConfirmPart1' => 'Bạn có chắc chắn muốn xóa không ', //mitcks: không thể sử dụng các tham số thông thường ở đây cho tên đầy đủ của người dùng vì nó tồn tại trong JS khi hàng được nhấp và không thể được sử dụng trong hàm chuyển
            'deleteConfirmPart2' => 'từ ',
            'waitlistDeleteConfirmPart2' => 'từ DANH SÁCH CHỜ cho :Event ?',
            'myWaitlistDeleteConfirmPart2' => 'từ DANH SÁCH CHỜ cho ',
            'parkConfirmPart1' => 'Bạn có chắc chắn muốn đậu xe không ', //mitcks: không thể sử dụng các tham số thông thường ở đây cho tên đầy đủ của người dùng vì nó tồn tại trong JS khi hàng được nhấp và không thể được sử dụng trong hàm chuyển
            'parkConfirmPart2' => 'từ ',
            'parkConfirmWaitlistText' => ' DANH SÁCH CHỜ cho ',
            'required' => 'Vui lòng điền vào tất cả các trường bắt buộc.',
            'access' => 'Bạn không có quyền truy cập để chỉnh sửa danh sách cho sự kiện vào :date.',
            'alreadyEnrolled' => ':user đã được đăng ký hoặc trong danh sách chờ <a href="/courseInstance/events/event-dashboard/:eventId/roster">:date</a>',
            'alreadyParked' => ':user đã có trong BÃI ĐỖ XE cho :courseAbbrv.',
            'moveSuccess' => ':user đã được chuyển thành công đến:date',
            'moveToParkingLotSuccess' => ':user đã được chuyển thành công đến BÃI ĐỖ XE cho :courseAbbrv.',
            'moveEventToEvent' => 'Bạn có chắc chắn muốn chuyển :name từ :fromDate đến ' , //toDate phải được chèn trong blade, không thể chuyển nó vào đây vì nó là biến JS
            'moveParkingLotToEvent' => 'Bạn có chắc chắn muốn chuyển :name từ BÃI ĐỖ XE đến ' , //toDate phải được chèn trong blade, không thể chuyển nó vào đây vì nó là biến JS
            'moveWaitListToEvent' => 'Bạn có chắc chắn muốn chuyển :name từ DANH SÁCH CHỜ trong :fromDate để được <span style=color:green>ENROLLED</span> ' , //toDate phải được chèn trong blade, không thể chuyển nó vào đây vì nó là biến JS
            'select_class_wall' => 'Chọn lớp',
            'select_class_text_wall' => 'Bạn phải chọn một lớp để chuyển người dùng này sang.',
            'unexpected' => 'Đã xảy ra lỗi không mong muốn khi di chuyển người dùng này. Vui lòng thử lại.',
            'unexpectedSelfPark' => 'Đã xảy ra lỗi không mong muốn khi thêm bạn vào danh sách chờ của khóa học. Xin vui lòng liên hệ :siteEmail để được hỗ trợ.',
            'selfParkSuccess' => 'Bạn đã được thêm thành công vào DANH SÁCH CHỜ cho :courseAbbrv.',
            'selfParkAlreadyParked' => 'Bạn đã ở trong DANH SÁCH CHỜ cho :courseAbbrv.',
        ],

        'eventuserrequest' => [
            'enrolled' => 'Bạn đã được đăng ký với tư cách là :role cho sự kiện vào :date.  
                            Đi đến <a href="/courseInstance/events/event-dashboard/:eventId">
                            Bảng điều khiển sự kiện</a>.',
            'waitlist' => 'Bạn đã có trong danh sách chờ đợi (:role) cho sự kiện vào :date.',
            'unexpected' => 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.',
            'success' => 'Yêu cầu đăng ký của bạn cho :date đã được nộp.',
            'no_approvers' => 'KHÔNG AI ĐƯỢC PHÉP DUYỆT YÊU CẦU ĐĂNG KÝ NÀY',

        ],

        'profile' => [
            'image' => 'Loại tệp được tải lên phải là loại hình ảnh được hỗ trợ.',
            'image_size' => 'Không tải lên được ảnh hồ sơ của bạn. Kích thước tối đa của hình ảnh là 2MB.'
        ],

        'emails' => [
            'edit' => 'The :label email đã được chỉnh sửa thành công.',
            'create' => 'The :label email đã được tạo thành công.',
            'delete' => 'The :label email đã bị xóa.',
            'image_size' => 'Không tải lên được ảnh hồ sơ của bạn. Kích thước tối đa của hình ảnh là 2MB.'
        ]

    ]
];
