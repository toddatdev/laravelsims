<?php

use Illuminate\Database\Seeder;

class CourseOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('course_options')->delete();
        
        \DB::table('course_options')->insert(array (
            0 => 
            array (
                'id' => 1,
                'description' => 'automatic_class_enrollment',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            2 => 
            array (
                'id' => 3,
                'description' => 'requires_completion',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            3 => 
            array (
                'id' => 4,
                'description' => 'offers_certificate',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            4 => 
            array (
                'id' => 5,
                'description' => 'hide_course_catalog',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            5 => 
            array (
                'id' => 6,
                'description' => 'hide_enrollment_button',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            6 => 
            array (
                'id' => 7,
                'description' => 'color',
                'input_type_id' => 4,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 8,
                'description' => 'z_setup_time',
                'input_type_id' => 9,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 9,
                'description' => 'z_teardown_time',
                'input_type_id' => 9,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 10,
                'description' => 'y_fac_report',
                'input_type_id' => 8,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 11,
                'description' => 'z_fac_leave',
                'input_type_id' => 10,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 12,
                'description' => 'hide_course_welcome_board',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 2,
                'description' => 'hide_course_calendar',
                'input_type_id' => 1,
                'created_at' => '2018-10-29 17:21:25',
                'updated_at' => '2018-10-29 17:21:25',
            ),
            array (
                'id' => 13,
                'description' => 'allow_request_when_closed',
                'input_type_id' => 1,
                'created_at' => '2021-05-21 00:00:00',
                'updated_at' => '2021-05-21 00:00:00',
            ),
        ));
        
        
    }
}