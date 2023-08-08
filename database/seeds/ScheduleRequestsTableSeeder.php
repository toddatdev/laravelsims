<?php

use Illuminate\Database\Seeder;

class ScheduleRequestsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('schedule_requests')->delete();
        
        \DB::table('schedule_requests')->insert(array (
            0 => 
            array (
                'id' => 3,
                'requested_by' => 49,
                'group_request_id' => 3,
                'course_id' => 15,
                'location_id' => 8,
                'start_time' => '2019-04-10 08:00:00',
                'end_time' => '2019-04-10 14:00:00',
                'template_id' => NULL,
                'num_rooms' => 5,
                'class_size' => 15,
                'sims_spec_needed' => 1,
                'notes' => 'Special Note',
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-09 14:39:33',
                'updated_at' => '2019-04-09 14:39:33',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 4,
                'requested_by' => 49,
                'group_request_id' => 4,
                'course_id' => 17,
                'location_id' => 9,
                'start_time' => '2019-04-11 15:45:00',
                'end_time' => '2019-04-11 17:45:00',
                'template_id' => NULL,
                'num_rooms' => 3,
                'class_size' => 25,
                'sims_spec_needed' => 0,
                'notes' => 'We need extra chairs',
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-09 14:50:13',
                'updated_at' => '2019-04-09 14:50:13',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 5,
                'requested_by' => 49,
                'group_request_id' => 5,
                'course_id' => 45,
                'location_id' => 22,
                'start_time' => '2019-04-10 10:00:00',
                'end_time' => '2019-04-10 12:00:00',
                'template_id' => NULL,
                'num_rooms' => 4,
                'class_size' => 15,
                'sims_spec_needed' => 1,
                'notes' => 'Note to test',
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-09 14:58:47',
                'updated_at' => '2019-04-09 14:58:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 6,
                'requested_by' => 1,
                'group_request_id' => 8,
                'course_id' => 55,
                'location_id' => 10,
                'start_time' => '2019-04-25 08:00:00',
                'end_time' => '2019-04-25 14:00:00',
                'template_id' => NULL,
                'num_rooms' => 2,
                'class_size' => 2,
                'sims_spec_needed' => 1,
                'notes' => NULL,
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-10 19:12:28',
                'updated_at' => '2019-04-10 19:12:28',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 7,
                'requested_by' => 1,
                'group_request_id' => 9,
                'course_id' => 51,
                'location_id' => 8,
                'start_time' => '2019-04-10 08:00:00',
                'end_time' => '2019-04-10 14:00:00',
                'template_id' => NULL,
                'num_rooms' => 2,
                'class_size' => 2,
                'sims_spec_needed' => 1,
                'notes' => NULL,
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-10 19:33:35',
                'updated_at' => '2019-04-10 19:33:35',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 8,
                'requested_by' => 1,
                'group_request_id' => 10,
                'course_id' => 19,
                'location_id' => 9,
                'start_time' => '2019-04-10 17:00:00',
                'end_time' => '2019-04-10 19:30:00',
                'template_id' => NULL,
                'num_rooms' => 1,
                'class_size' => 2,
                'sims_spec_needed' => 1,
                'notes' => NULL,
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-10 21:19:28',
                'updated_at' => '2019-04-10 21:19:28',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 9,
                'requested_by' => 50,
                'group_request_id' => 11,
                'course_id' => 25,
                'location_id' => 9,
                'start_time' => '2019-05-08 15:00:00',
                'end_time' => '2019-05-08 17:00:00',
                'template_id' => NULL,
                'num_rooms' => 2,
                'class_size' => 3,
                'sims_spec_needed' => 0,
                'notes' => 'This is an internal note.',
                'event_id' => NULL,
                'denied_by' => NULL,
                'denied_date' => NULL,
                'created_at' => '2019-04-11 13:12:52',
                'updated_at' => '2019-04-11 13:12:52',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}