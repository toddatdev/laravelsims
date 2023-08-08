<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute phải được chấp nhận.',
    'active_url'           => 'The :attribute không phải là một URL hợp lệ.',
    'after'                => 'The :attribute phải là một ngày sau :date.',
    'after_or_equal'       => 'The :attribute phải là một ngày sau hoặc bằng :date.',
    'alpha'                => 'The :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash'           => 'The :attribute chỉ có thể chứa các chữ cái, số và dấu gạch ngang.',
    'alpha_num'            => 'The :attribute chỉ có thể chứa các chữ cái và số.',
    'array'                => 'The :attribute phải là một dãy.',
    'before'               => 'The :attribute phải là một ngày trước :date.',
    'before_or_equal'      => 'The :attribute phải là một ngày trước hoặc bằng :date.',
    'between'              => [
        'numeric' => 'The :attribute phải ở giữa :min và :max.',
        'file'    => 'The :attribute phải ở giữa :min và :max kilobytes.',
        'string'  => 'The :attribute phải ở giữa :min và :max kí tự.',
        'array'   => 'The :attribute phải có giữa :min và :max mục.',
    ],
    'boolean'              => 'The :attribute trường phải đúng hoặc sai.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute xác nhận không phù hợp.',
    'date_format'          => 'The :attribute không phù hợp với định dạng :format.',
    'different'            => 'The :attribute và :other phải khác nhau.',
    'digits'               => 'The :attribute phải là :digits chữ số.',
    'digits_between'       => 'The :attribute phải ở giữa :min và :max chữ số.',
    'dimensions'           => 'The :attribute có kích thước hình ảnh không hợp lệ.',
    'distinct'             => 'The :attribute trường có giá trị trùng lặp.',
    'email'                => 'The :attribute phải là địa chỉ email hợp lệ.',
    'exists'               => 'The selected :attribute không có hiệu lực.',
    'file'                 => 'The :attribute phải là một tập tin.',
    'filled'               => 'The :attribute trường phải có một giá trị.',
    'image'                => 'The :attribute phải là một hình ảnh.',
    'in'                   => 'The selected :attribute không có hiệu lực.',
    'in_array'             => 'The :attribute trường không tồn tại trong :other.',
    'integer'              => 'The :attribute phải là số nguyên.',
    'ip'                   => 'The :attribute phải là một địa chỉ IP hợp lệ.',
    'ipv4'                 => 'The :attribute phải là một địa chỉ IPv4 hợp lệ.',
    'ipv6'                 => 'The :attribute phải là một địa chỉ IPv6 hợp lệ.',
    'json'                 => 'The :attribute phải là một chuỗi JSON hợp lệ.',
    'max'                  => [
        'numeric' => 'The :attribute có thể không lớn hơn :max.',
        'file'    => 'The :attribute có thể không có nhiều hơn :max kilobytes.',
        'string'  => 'The :attribute phải là một loại tệp :max kí tự.',
        'array'   => 'The :attribute có thể không có nhiều hơn :max mục.',
    ],
    'mimes'                => 'The :attribute phải là một loại tệp type: :values.',
    'mimetypes'            => 'The :attribute phải là một loại tệp type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute ít nhất phải là :min.',
        'file'    => 'The :attribute phải có ít nhất :min kilobytes.',
        'string'  => 'The :attribute phải có ít nhất :min kí tự.',
        'array'   => 'The :attribute phải có ít nhất :min mục.',
    ],
    'not_in'               => 'The selected :attribute không có hiệu lực.',
    'numeric'              => 'The :attribute phải là một số.',
    'present'              => 'The :attribute trường phải tồn tại.',
    'regex'                => 'The :attribute định dạng không hợp lệ.',
    'required'             => 'The :attribute trường bắt buộc.',
    'required_if'          => 'The :attribute trường bắt buộc khi :other là :value.',
    'required_unless'      => 'The :attribute trường bắt buộc nếu không :other là trong :values.',
    'required_with'        => 'The :attribute trường bắt buộc khi :values tồn tại.',
    'required_with_all'    => 'The :attribute trường bắt buộc khi :values tông tại.',
    'required_without'     => 'The :attribute trường bắt buộc khi :values không tồn tại.',
    'required_without_all' => 'The :attribute trường là bắt buộc khi không có :values tông tại.',
    'same'                 => 'The :attribute và :other phải phù hợp.',
    'size'                 => [
        'numeric' => 'The :attribute phải là :size.',
        'file'    => 'The :attribute phải là :size kilobytes.',
        'string'  => 'The :attribute phải là :size kí tự.',
        'array'   => 'The :attribute phải chứa :size mục.',
    ],
    'string'               => 'The :attribute phải là một chuỗi.',
    'timezone'             => 'The :attribute phải là một vùng hợp lệ.',
    'unique'               => 'The :attribute phải là duy nhất.',
    'uploaded'             => 'The :attribute không tải lên được.',
    'url'                  => 'The :attribute định dạng không hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'Thông Báo Tùy Chỉnh',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [

        'backend' => [
            'access' => [
                'permissions' => [
                    'associated_roles' => 'Vai Trò Phụ',
                    'dependencies'     => 'Phụ Thuộc',
                    'display_name'     => 'Tên Hiển Thị',
                    'group'            => 'Nhóm',
                    'group_sort'       => 'Sắp Xếp Theo Nhóm',

                    'groups' => [
                        'name' => 'Tên Nhóm',
                    ],

                    'name'       => 'Họ Tên',
                    'first_name' => 'Tên',
                    'last_name'  => 'Họ',
                    'middle_name' => 'Tên Lót',
                    'system'     => 'Hệ Thống',
                ],

                'roles' => [
                    'associated_permissions' => 'Cho Phép Kết Nối',
                    'name'                   => 'Họ Tên',
                    'sort'                   => 'Sắp Xếp',
                ],

                'users' => [
                    'active'                  => 'Hoạt Động',
                    'associated_roles'        => 'Vai Trò Phụ',
                    'confirmed'               => 'Đã Xác Nhận',
                    'email'                   => 'Địa chỉ E-mail',
                    'name'                    => 'Họ Tên',
                    'last_name'               => 'Họ',
                    'first_name'              => 'Tên',
                    'middle_name'             => 'Tên Lót',
                    'phone'                   => 'Số Điện Thoại',
                    'other_permissions'       => 'Các Quyền Khác',
                    'password'                => 'Mật Khẩu',
                    'password_confirmation'   => 'Xác Nhận Mật Khẩu',
                    'send_confirmation_email' => 'Gửi Xác Nhận Qua E-mail',
                ],
            ],
        ],

        'frontend' => [
            // 'email'                     => 'Địa Chỉ email',
            'email'                     => 'Địa Chỉ E-mail',
            'first_name'                => 'Tên',
            'middle_name'               => 'Tên Lót',
            'last_name'                 => 'Họ',
            'phone'                     => 'Số Điện Thoại',
            'profile_picture'           => 'Ảnh Đại Diện',
            // 'password'                  => 'Mật Khẩu',
            'password'                  => 'Mật Khẩu',
            'password_confirmation'     => 'Xác Nhận Mật Khẩu',
            'old_password'              => 'Mật Khẩu Cũ',
            'new_password'              => 'Mật Khẩu Mới',
            'new_password_confirmation' => 'Xác Nhận Mật Khẩu Mới',

            // I added this for the modal about the phone number on the user page. -jl 2018-04-02 14:40
            'why'                       => 'Tại sao?',
            'why_phone_ques'            => 'Tại Sao Bạn Cần Số Điện Thoại Của Tôi?',
            'why_phone_answer'          => ':site_abbrv có thể cần liên hệ với bạn để thông báo cho bạn về các sự kiện bị hủy hoặc thay đổi. Số điện thoại này sẽ chỉ được sử dụng bởi :site_name để biết thông tin về chương trình.<br/><br/>Nhập số điện thoại của bạn là <b>optional</b>.<br/><br/>Mọi thắc mắc bạn có thể liên hệ :site_abbrv at <a href="mailto::site_email">:site_email</a>.',
            'close'                     => 'Đóng',

        ],

        'site' => [
            'abbrv'                     => 'Viết Tắt',
            'name'                      => 'Tên Đầy Đủ',
            'organization_name'         => 'Tên Tổ Chức',
            'email'                     => 'Email',
        ],

        'user-profile-questions' => [
            'question'                  => 'Câu Hỏi',
            'answer'                    => 'Câu Trả Lời',
        ],

        'event_users' => [
            'user'                     => 'Người Dùng',
            'event'                    => 'Sự Kiện',
            'role'                     => 'Vai Trò',
            'move_event_id'            => 'Bạn phải chọn một sự kiện',
            'move_event_user'          => 'Bạn phải di chuyển một người dùng',
            'duplicate'                => 'Bạn đã yêu cầu sự kiện này cho vai trò này.',
        ],

        'schedule_comment' => [
            'comment'                  => 'Comment',
            'max_size'                 => 'Number of Characters',
        ],

        'course' => [
            'abbrv'                     => 'Viết Tắt',
            'name'                      => 'Tền Đầy Đủ Khóa Học',
            'catalog_description'       => 'Mô Tả Danh Mục',
            'author_name'               => 'Tên Tác Giả',
            'catalog_image'             => 'Hình Ảnh Danh Mục',
        ],

        'course_template' => [
            'name'                      => 'Tên Mẫu',
            'class_size'                => 'Số Người Tham gia',
            'setup'                     => 'Thời Gian Cài Đặt',
            'teardown'                  => 'teardown time',
            'imr'                       => 'Phòng Họp Ban Đầu',
            'course_id'                 => 'Khóa Học',
            'resource_name'             => 'Tên Tài Nguyên',
            'resource_start_time'       => 'Thời Gian Bắt Đầu Tài Nguyên',
            'resource_end_time'         => 'Thời Gian Kết Thúc Tài Nguyên',
            'resource_start_gt_end'              => 'Thời gian kết thúc tài nguyên phải lớn hơn thời gian bắt đầu'
        ],

        'course_users' => [
            'user'                     => 'Tìm Kiếm Người Dùng',
            'course'                   => 'Khóa Học',
            'role'                     => 'Vai Trò Khóa Học',
        ],

        'site_users' => [
            'user'                     => 'Tìm Kiếm Người Dùng',
            'role'                     => 'Vai Trò Trang Web',
        ],


        'courseCategoryGroup' => [
            'abbrv'                     => 'Viết Tắt',
            'description'               => 'Mô Tả',
        ],


        'resource' => [
            'abbrv'                     => 'Viết Tắt',
            'full_name'                 => 'Tên Đầy Đủ',
            'location'                  => 'Địa Điểm',
            'category'                  => 'Danh Mục',
            'subcategory'               => 'Danh Mục Phụ',
            'type'                      =>  'Loại',
        ],

        'resourceCategory' => [
            'abbrv'                     => 'Viết Tắt',
            'full_name'                 => 'Tên Đầy Đủ',
        ],

        'resourceSubCategory' => [
            'abbrv'                     => 'Viết Tắt',
            'full_name'                 => 'Tên Đầy Đủ',
        ],

        'courseCategories' => [
            'abbrv'                     => 'Viết Tắt',
            'description'               => 'Mô Tả',
        ],

        'buildings' => [
            'create_building_box_title' => 'Tạo Cơ Sở Mới',
            'id'                        => 'ID Cơ Sở',
            'abbrv'                     => 'Viết Tắt',
            'name'                      => 'Tên Đầy Đủ Cơ Sở',
            'more_info'                 => 'Thông Tin Thêm',
            'map_url'                   => 'Bản đồ URL',
            'address'                   => 'Đường',
            'city'                      => 'Thành Phố',
            'state'                     => 'Quận/Huyện',
            'postal_code'               => 'Mã Bưu Điện',
            'timezone'                  => 'Múi Giờ',
            'timezone_help'             => 'Múi giờ ở định dạng Vùng/Thành phố',
            'display_order'             => 'Thứ Tự Hiển Thị',
            'create_button'             => 'Tạo',
            'update_button'             => 'Cập Nhật',
        ],
        'schedule' => [
            'course_id'                 => 'Khóa Học',
            'location_id'               => 'Địa Điểm',
            'class_size'                => 'Số Lượng Người Tham Gia',
            'num_rooms'                 => 'Số Lượng Phòng',
            'fac_report'                => 'Hồ Sơ Giảng Viên',
            'fac_leave'                 => 'Giảng Viên Nghỉ',
            'start_time'                => 'Thời Gian Bắt Đầu',
            'end_time'                  => 'Thời Gian Kết Thúc',
            'event_date'                => 'Ngày Sự Kiện',
            'initial_meeting_room'      => 'Phòng Họp Ban Đầu',
        ],
        'locations' => [
            'create_location_box_title' => 'Tạo Địa Điểm Mới',
            'id'                        => 'ID Địa Điểm',
            'abbrv'                     => 'Viết Tắt',
            'name'                      => 'Tên Đầy Đủ Địa Điểm',
            'more_info'                 => 'Thông Tin Thêm',
            'directions_url'            => 'URL Chỉ Dẫn',
            'display_order'             => 'Thứ Tự Hiển Thị',
            'create_button'             => 'Tạo',
            'update_button'             => 'Cập Nhật',
            'unique'                    => 'The :attribute phải là duy nhất trong cơ sở.',

        ],
        'siteEmails' => [
            'type_course_rule'          => 'Loại trang web Email không thể là Email khóa học.'
        ],

    ],

];
