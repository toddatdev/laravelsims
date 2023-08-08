<?php

return [

    /*
    |--------------------------------------------------------------------------
    | History Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain strings associated to the
    | system adding lines to the history table.
    |
    */

    'backend' => [
        'none'            => 'Không có lịch sử gần đây.',
        'none_for_type'   => 'Không có lịch sử cho loại này.',
        'none_for_entity' => 'Không có lịch sử cho điều này :entity.',
        'recent_history'  => 'Lịch sử gần đây',

        'roles' => [
            'created' => 'vai trò đã được tạo',
            'deleted' => 'vai trò đã được xóa',
            'updated' => 'vai trò đã được cập nhật',
        ],
        'users' => [
            'changed_password'    => 'đã thay đổi mật khẩu cho người dùng',
            'created'             => 'người dùng đã được tạo',
            'deactivated'         => 'người dùng đã hủy kích hoạt',
            'deleted'             => 'đã xóa người dùng',
            'permanently_deleted' => 'người dùng bị xóa vĩnh viễn',
            'updated'             => 'người dùng đã được cập nhật',
            'reactivated'         => 'người dùng được kích hoạt lại',
            'restored'            => 'người dùng đã được khôi phục',
        ],
    ],

    'event_user' => [
        'enrolled'  => 'Đã đăng ký với tư cách :role',
        'removed'   => 'Bị loại khỏi :role vai trò',
        'moved'     => 'Chuyển từ :old_event đến :new_event',
        'request_access'  => 'Yêu cầu quyền truy cập với tư cách :role',

    ],
];
