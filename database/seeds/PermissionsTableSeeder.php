<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('permissions')->delete();

        // https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sqlmode_no_auto_value_on_zero
        DB::table('permissions')->insert(array (
            0 =>
            array (
                'id' => 0,
                'name' => 'null-permission',
                'display_name' => 'No Additional Permissions',
                'sort' => 0,
                'client_visible' => 1,
                'help_text' => 'No additional site permissions. A role with no permissions should have only this permission in it and no others.',
                'permission_type_id' => 1,
                'created_at' => '2019-06-24 17:04:11',
                'updated_at' => '2019-06-24 17:04:11',
            ),
            1 => 
            array (
                'id' => 1,
                'name' => 'view-backend',
                'display_name' => 'View Site Administration',
                'sort' => 1,
                'client_visible' => 1,
                'help_text' => 'Allows the user to view the backend adminstrative area. It does not provide any individual permissions, but if you need access to functionality there you need to have this permission as well.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 15:00:27',
                'updated_at' => '2018-02-28 15:00:27',
            ),
            2 => 
            array (
                'id' => 2,
                'name' => 'manage-sites',
                'display_name' => 'Manage SIMS Sites',
                'sort' => 2,
                'client_visible' => 0,
                'help_text' => 'Allows the user to Manage SIMS SITES.  ONLY SIMMEDICAL PEOPLE SHOULD HAVE ACCESS TO THIS.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 15:01:36',
                'updated_at' => '2018-02-28 15:01:36',
            ),
            3 => 
            array (
                'id' => 3,
                'name' => 'manage-users',
                'display_name' => 'Manage Users',
                'sort' => 3,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and deactivate users. Allows the user to assign roles to user accounts.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 15:03:37',
                'updated_at' => '2018-02-28 15:03:37',
            ),
            4 => 
            array (
                'id' => 4,
                'name' => 'manage-courses',
                'display_name' => 'Manage Courses',
                'sort' => 4,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and retire courses.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 15:04:00',
                'updated_at' => '2018-02-28 15:04:00',
            ),
            5 => 
            array (
                'id' => 5,
                'name' => 'manage-buildings',
                'display_name' => 'Manage Buildings',
                'sort' => 5,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and retire buildings in the system.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 16:25:04',
                'updated_at' => '2018-02-28 16:25:04',
            ),
            6 => 
            array (
                'id' => 6,
                'name' => 'manage-roles',
                'display_name' => 'Manage Roles',
                'sort' => 6,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and delete roles in the system.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 16:44:14',
                'updated_at' => '2018-02-28 16:44:14',
            ),
            7 => 
            array (
                'id' => 7,
                'name' => 'manage-locations',
                'display_name' => 'Manage Locations',
                'sort' => 7,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and retire locations in the system.',
                'permission_type_id' => 1,
                'created_at' => '2018-02-28 17:42:48',
                'updated_at' => '2018-02-28 17:42:48',
            ),
            8 => 
            array (
                'id' => 8,
                'name' => 'manage-profiles',
                'display_name' => 'Manage User Profiles',
                'sort' => 8,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and inactivate the profile questions and answers.',
                'permission_type_id' => 1,
                'created_at' => '2018-10-11 14:22:35',
                'updated_at' => '2018-10-11 14:22:41',
            ),
            9 => 
            array (
                'id' => 9,
                'name' => 'scheduling',
                'display_name' => 'Manage Scheduling',
                'sort' => 9,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and delete scheduled items on the calendar and make comments on any event on the calendar.',
                'permission_type_id' => 1,
                'created_at' => '2018-11-14 12:00:00',
                'updated_at' => '2018-11-14 12:00:00',
            ),
            10 => 
            array (
                'id' => 10,
                'name' => 'event-details',
                'display_name' => 'View Event Details',
                'sort' => 10,
                'client_visible' => 1,
            'help_text' => 'Allows the user to see the event details for a particular event (Internal Comments, who and when created, etc.)',
                'permission_type_id' => 1,
                'created_at' => '2018-11-14 12:00:00',
                'updated_at' => '2018-11-14 12:00:00',
            ),
            11 => 
            array (
                'id' => 11,
                'name' => 'schedule-request',
                'display_name' => 'Schedule Request',
                'sort' => 11,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create schedule requests for any active course in the system',
                'permission_type_id' => 1,
                'created_at' => '2019-02-11 12:00:00',
                'updated_at' => '2019-02-11 12:00:00',
            ),
            12 => 
            array (
                'id' => 12,
                'name' => 'manage-templates',
                'display_name' => 'Manage Templates',
                'sort' => 12,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and delete any course templates in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-04-05 12:00:00',
                'updated_at' => '2019-04-05 12:00:00',
            ),
            13 => 
            array (
                'id' => 13,
                'name' => 'course-options',
                'display_name' => 'Manage Course Options',
                'sort' => 13,
                'client_visible' => 1,
                'help_text' => 'Allows the user to set any options for any courses in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-04-05 12:00:00',
                'updated_at' => '2019-04-05 12:00:00',
            ),
            14 => 
            array (
                'id' => 14,
                'name' => 'course_categories',
                'display_name' => 'Manage Course Categories',
                'sort' => 14,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete any course categories, as well as assign categories to any course in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-04-05 12:00:00',
                'updated_at' => '2019-04-05 12:00:00',
            ),
            15 => 
            array (
                'id' => 15,
                'name' => 'manage-resources',
                'display_name' => 'Manage Resources',
                'sort' => 15,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, and retire any resources in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-04-17 13:03:35',
                'updated_at' => '2019-04-17 13:03:35',
            ),
            16 => 
            array (
                'id' => 16,
                'name' => 'course-schedule-request',
                'display_name' => 'Course Schedule Request',
                'sort' => 1,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create schedule requests for specifc courses they are assigned to.',
                'permission_type_id' => 2,
                'created_at' => '2019-06-13 10:20:30',
                'updated_at' => '2019-06-24 17:03:55',
            ),
            17 => 
            array (
                'id' => 17,
                'name' => 'course-add-people-to-events',
                'display_name' => 'Course Add People To Events',
                'sort' => 2,
                'client_visible' => 1,
                'help_text' => 'Add people to any event role for a specific course.',
                'permission_type_id' => 2,
                'created_at' => '2019-06-24 17:03:53',
                'updated_at' => '2019-06-24 17:03:53',
            ),
            18 => 
            array (
                'id' => 18,
                'name' => 'event-add-people-to-events',
                'display_name' => 'Event Add People to Events',
                'sort' => 1,
                'client_visible' => 1,
                'help_text' => 'Add people to any event that they are already assigned to.',
                'permission_type_id' => 3,
                'created_at' => '2019-06-24 17:03:58',
                'updated_at' => '2019-06-24 17:04:02',
            ),
            19 => 
            array (
                'id' => 19,
                'name' => 'add-people-to-events',
                'display_name' => 'Site Add People to Events',
                'sort' => 16,
                'client_visible' => 1,
                'help_text' => 'Add people to any event for any course in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-06-24 17:04:07',
                'updated_at' => '2019-06-24 17:04:07',
            ),
            20 => 
            array (
                'id' => 22,
                'name' => 'course-null-permissions',
                'display_name' => 'No Course Permissions',
                'sort' => 3,
                'client_visible' => 1,
                'help_text' => 'No additional Course permissions. A role with no permisisons should have only this permission in it and no others.',
                'permission_type_id' => 2,
                'created_at' => '2019-06-19 09:00:54',
                'updated_at' => '2019-06-19 09:00:54',
            ),
            21 => 
            array (
                'id' => 23,
                'name' => 'event-null-permissions',
                'display_name' => 'No Event Permissions',
                'sort' => 3,
                'client_visible' => 1,
                'help_text' => 'No additional Event permissions. A role with no permisisons should have only this permission in it and no others.',
                'permission_type_id' => 3,
                'created_at' => '2019-06-19 09:03:11',
                'updated_at' => '2019-06-19 09:03:11',
            ),
            22 => 
            array (
                'id' => 24,
                'name' => 'course-add-people-to-courses',
                'display_name' => 'Course Add People to Course',
                'sort' => 18,
                'client_visible' => 1,
                'help_text' => 'Allows the user to add people to course level roles.',
                'permission_type_id' => 2,
                'created_at' => '2019-08-23 11:44:48',
                'updated_at' => '2019-08-23 11:44:48',
            ),
            23 => 
            array (
                'id' => 25,
                'name' => 'add-event-comment',
                'display_name' => 'Add Event Comment',
                'sort' => 19,
                'client_visible' => 1,
                'help_text' => 'Allow user to see and add comments within an event.',
                'permission_type_id' => 1,
                'created_at' => '2019-08-26 13:41:12',
                'updated_at' => '2019-08-26 13:41:12',
            ),
            24 => 
            array (
                'id' => 26,
                'name' => 'course-add-event-comment',
                'display_name' => 'Course Add Event Comment',
                'sort' => 20,
                'client_visible' => 1,
                'help_text' => 'Allow course user to see and add comments within an event.',
                'permission_type_id' => 2,
                'created_at' => '2019-08-26 13:42:51',
                'updated_at' => '2019-08-26 13:42:51',
            ),
            25 => 
            array (
                'id' => 27,
                'name' => 'client-manage-site-options',
                'display_name' => 'Client Manage Site Options',
                'sort' => 17,
                'client_visible' => 1,
                'help_text' => 'Allow user to manage site\'s options.',
                'permission_type_id' => 1,
                'created_at' => '2019-08-26 14:49:25',
                'updated_at' => '2019-08-26 14:49:25',
            ),
            26 => 
            array (
                'id' => 28,
                'name' => 'client-manage-site-email',
                'display_name' => 'Client Manage Site Email',
                'sort' => 21,
                'client_visible' => 1,
                'help_text' => 'Allow user to manage the sites email.',
                'permission_type_id' => 1,
                'created_at' => '2019-08-26 15:10:37',
                'updated_at' => '2019-08-26 15:10:37',
            ),
            27 => 
            array (
                'id' => 29,
                'name' => 'add-people-to-courses',
                'display_name' => 'Site Add People to Course',
                'sort' => 22,
                'client_visible' => 1,
                'help_text' => 'Site add people to a course in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-10-14 16:02:19',
                'updated_at' => '2019-10-14 16:02:19',
            ),
            28 => 
            array (
                'id' => 30,
                'name' => 'manage-course-emails',
                'display_name' => 'Manage Course Emails',
                'sort' => 23,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete, and send any course level emails in the system',
                'permission_type_id' => 1,
                'created_at' => '2019-10-17 08:26:49',
                'updated_at' => '2019-10-17 08:26:49',
            ),
            29 => 
            array (
                'id' => 31,
                'name' => 'course-manage-course-emails',
                'display_name' => 'Course Manage Course Level Emails',
                'sort' => 24,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete, and send emails for courses that they are assigned to.',
                'permission_type_id' => 2,
                'created_at' => '2019-10-17 08:28:58',
                'updated_at' => '2019-10-17 08:28:58',
            ),
            30 => 
            array (
                'id' => 32,
                'name' => 'manage-event-emails',
                'display_name' => 'Manage Event Emails',
                'sort' => 25,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete, and send any event emails in the system.',
                'permission_type_id' => 1,
                'created_at' => '2019-10-17 08:38:52',
                'updated_at' => '2019-10-17 08:38:52',
            ),
            31 => 
            array (
                'id' => 33,
                'name' => 'course-manage-event-emails',
                'display_name' => 'Course Manage Event Level Emails',
                'sort' => 26,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete, and send emails for courses that they are assigned to.',
                'permission_type_id' => 2,
                'created_at' => '2019-10-17 08:40:06',
                'updated_at' => '2019-10-17 08:40:06',
            ),
            32 => 
            array (
                'id' => 34,
                'name' => 'event-manage-event-emails',
                'display_name' => 'Event Manage Event Level Emails',
                'sort' => 27,
                'client_visible' => 1,
                'help_text' => 'Allows the user to create, edit, delete, and send email for events that they are assigned to.',
                'permission_type_id' => 3,
                'created_at' => '2019-10-17 08:44:14',
                'updated_at' => '2019-10-17 08:44:14',
            ),
            33 => 
            array (
                'id' => 35,
                'name' => 'event-add-event-comment',
                'display_name' => 'Event Add Event Comment',
                'sort' => 28,
                'client_visible' => 1,
                'help_text' => 'Allows the user to see and add comments to an event that they are assigned to.',
                'permission_type_id' => 3,
                'created_at' => '2020-03-12 08:44:14',
                'updated_at' => '2020-03-12 08:44:14',
            ),
            33 =>
                array (
                    'id' => 36,
                    'name' => 'manage-course-curriculum',
                    'display_name' => 'Site Manage Course Curriculum',
                    'sort' => 29,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to manage curriculum for any course.',
                    'permission_type_id' => 1,
                    'created_at' => '2020-05-12',
                    'updated_at' => '2020-05-12',
                ),
            34 =>
                array (
                    'id' => 37,
                    'name' => 'course-manage-course-curriculum',
                    'display_name' => 'Course Manage Course Curriculum.',
                    'sort' => 30,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to manage curriculum for a specific course.',
                    'permission_type_id' => 2,
                    'created_at' => '2020-05-12',
                    'updated_at' => '2020-05-12'
                ),
            35 =>
                array (
                    'id' => 38,
                    'name' => 'event-view-learner-curriculum',
                    'display_name' => 'Event View Learner Curriculum',
                    'sort' => 31,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view learner content for a specific event.',
                    'permission_type_id' => 3,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            36 =>
                array (
                    'id' => 39,
                    'name' => 'event-view-instructor-curriculum',
                    'display_name' => 'Event View Instructor Curriculum',
                    'sort' => 32,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view instructor content for a specific event.',
                    'permission_type_id' => 3,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            37 =>
                array (
                    'id' => 40,
                    'name' => 'view-learner-curriculum',
                    'display_name' => 'View All Learner Curriculum',
                    'sort' => 33,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view learner content for any course.',
                    'permission_type_id' => 1,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            38 =>
                array (
                    'id' => 41,
                    'name' => 'course-view-learner-curriculum',
                    'display_name' => 'Course View Learner Curriculum',
                    'sort' => 34,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view learner content for a specific course.',
                    'permission_type_id' => 2,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            39 =>
                array (
                    'id' => 42,
                    'name' => 'view-instructor-curriculum',
                    'display_name' => 'View All Instructor Curriculum',
                    'sort' => 35,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view instructor content for any course.',
                    'permission_type_id' => 1,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            40 =>
                array (
                    'id' => 43,
                    'name' => 'course-view-instructor-curriculum',
                    'display_name' => 'Course View Instructor Curriculum',
                    'sort' => 36,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to view instructor content for a specific course.',
                    'permission_type_id' => 2,
                    'created_at' => '2020-06-18',
                    'updated_at' => '2020-06-18'
                ),
            41 =>
                array (
                    'id' => 44,
                    'name' => 'manage-qse',
                    'display_name' => 'Site Manage QSE',
                    'sort' => 37,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to manage QSE for all events for a site.',
                    'permission_type_id' => 1,
                    'created_at' => '2021-01-12',
                    'updated_at' => '2021-01-12'
                ),
            42 =>
                array (
                    'id' => 45,
                    'name' => 'course-manage-qse',
                    'display_name' => 'Course Manage QSE',
                    'sort' => 38,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to manage QSE for all events for a specific course.',
                    'permission_type_id' => 2,
                    'created_at' => '2021-01-12',
                    'updated_at' => '2021-01-12'
                ),
            43 =>
                array (
                    'id' => 46,
                    'name' => 'event-manage-qse',
                    'display_name' => 'Event Manage QSE',
                    'sort' => 39,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to manage QSE for a specific event.',
                    'permission_type_id' => 3,
                    'created_at' => '2021-01-12',
                    'updated_at' => '2021-01-12'
                ),
            44 =>
                array (
                    'id' => 47,
                    'name' => 'site-report-creation',
                    'display_name' => 'Create Site Reports',
                    'sort' => 40,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to create site level reports.',
                    'permission_type_id' => 1,
                    'created_at' => '2021-01-12',
                    'updated_at' => '2021-01-12'
                ),
            45 =>
                array (
                    'id' => 48,
                    'name' => 'site-mark-event-attendance',
                    'display_name' => 'Site Mark Attendance',
                    'sort' => 41,
                    'client_visible' => 1,
                    'help_text' => 'Allows user to mark attendance for all courses.',
                    'permission_type_id' => 1,
                    'created_at' => '2021-02-17',
                    'updated_at' => '2021-02-17'
                ),
            46 =>
                array (
                    'id' => 49,
                    'name' => 'course-mark-event-attendance',
                    'display_name' => 'Course Mark Attendance',
                    'sort' => 46,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to mark attendance for a specific course.',
                    'permission_type_id' => 2,
                    'created_at' => '2021-02-17',
                    'updated_at' => '2021-02-17'
                ),
            47 =>
                array (
                    'id' => 50,
                    'name' => 'event-mark-event-attendance',
                    'display_name' => 'Event Mark Attendance',
                    'sort' => 40,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to mark attendance for a specific event.',
                    'permission_type_id' => 3,
                    'created_at' => '2021-02-17',
                    'updated_at' => '2021-02-17'
                ),
            48 =>
                array (
                    'id' => 51,
                    'name' => 'manage_course_fees',
                    'display_name' => 'Manage Course Fees',
                    'sort' => 42,
                    'client_visible' => 1,
                    'help_text' => 'Allows the user to create, edit, and retire course fees.',
                    'permission_type_id' => 1,
                    'created_at' => '2021-03-11',
                    'updated_at' => '2021-03-11'
                ),
        ));
    }
}
