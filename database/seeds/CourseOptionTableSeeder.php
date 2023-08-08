<?php

use Illuminate\Database\Seeder;

class CourseOptionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('course_option')->delete();

        // Removed option_id 2 rows as it fails constraint in the course_options table
        \DB::table('course_option')->insert(array (
            0 => 
            array (
                'id' => 23,
                'course_id' => 2,
                'option_id' => 1,
                'value' => '1',
                'created_at' => '2018-11-12 21:38:16',
                'updated_at' => '2018-11-12 21:38:16',
            ),
//            1 =>
//            array (
//                'id' => 24,
//                'course_id' => 2,
//                'option_id' => 2,
//                'value' => '1',
//                'created_at' => '2018-11-12 21:38:16',
//                'updated_at' => '2018-11-12 21:38:16',
//            ),
            2 => 
            array (
                'id' => 25,
                'course_id' => 2,
                'option_id' => 3,
                'value' => '1',
                'created_at' => '2018-11-12 21:38:16',
                'updated_at' => '2018-11-12 21:38:16',
            ),
            3 => 
            array (
                'id' => 29,
                'course_id' => 2,
                'option_id' => 7,
            'value' => 'rgb(255, 0, 0)',
                'created_at' => '2018-11-12 21:38:16',
                'updated_at' => '2018-11-28 17:22:24',
            ),
            4 => 
            array (
                'id' => 31,
                'course_id' => 2,
                'option_id' => 6,
                'value' => '1',
                'created_at' => '2018-11-12 21:41:43',
                'updated_at' => '2018-11-12 21:41:43',
            ),
            5 => 
            array (
                'id' => 32,
                'course_id' => 2,
                'option_id' => 8,
                'value' => '45',
                'created_at' => '2018-11-28 17:26:39',
                'updated_at' => '2019-01-10 13:53:50',
            ),
            6 => 
            array (
                'id' => 34,
                'course_id' => 2,
                'option_id' => 9,
                'value' => '30',
                'created_at' => '2018-11-28 19:08:49',
                'updated_at' => '2018-11-28 19:16:21',
            ),
            7 => 
            array (
                'id' => 37,
                'course_id' => 2,
                'option_id' => 10,
                'value' => '-15',
                'created_at' => '2018-11-28 20:24:21',
                'updated_at' => '2018-11-28 20:24:42',
            ),
            8 => 
            array (
                'id' => 38,
                'course_id' => 2,
                'option_id' => 11,
                'value' => '15',
                'created_at' => '2018-11-28 20:24:21',
                'updated_at' => '2018-11-28 20:24:42',
            ),
            9 => 
            array (
                'id' => 39,
                'course_id' => 2,
                'option_id' => 5,
                'value' => '1',
                'created_at' => '2018-11-28 20:26:09',
                'updated_at' => '2018-11-28 20:26:09',
            ),
//            10 =>
//            array (
//                'id' => 40,
//                'course_id' => 9,
//                'option_id' => 2,
//                'value' => '1',
//                'created_at' => '2019-01-17 15:10:01',
//                'updated_at' => '2019-01-17 15:10:01',
//            ),
            11 => 
            array (
                'id' => 41,
                'course_id' => 32,
                'option_id' => 5,
                'value' => '1',
                'created_at' => '2019-01-18 20:25:44',
                'updated_at' => '2019-01-18 20:25:44',
            ),
            12 => 
            array (
                'id' => 42,
                'course_id' => 32,
                'option_id' => 8,
                'value' => '120',
                'created_at' => '2019-01-18 20:25:44',
                'updated_at' => '2019-01-18 20:25:44',
            ),
            13 => 
            array (
                'id' => 43,
                'course_id' => 32,
                'option_id' => 9,
                'value' => '120',
                'created_at' => '2019-01-18 20:25:44',
                'updated_at' => '2019-01-18 20:25:44',
            ),
            14 => 
            array (
                'id' => 44,
                'course_id' => 32,
                'option_id' => 10,
                'value' => '-120',
                'created_at' => '2019-01-18 20:25:44',
                'updated_at' => '2019-01-18 20:25:44',
            ),
            15 => 
            array (
                'id' => 45,
                'course_id' => 32,
                'option_id' => 11,
                'value' => '-120',
                'created_at' => '2019-01-18 20:25:44',
                'updated_at' => '2019-01-18 20:25:44',
            ),
            16 => 
            array (
                'id' => 46,
                'course_id' => 10,
                'option_id' => 6,
                'value' => '1',
                'created_at' => '2019-01-18 20:50:36',
                'updated_at' => '2019-01-18 20:50:36',
            ),
            17 => 
            array (
                'id' => 47,
                'course_id' => 15,
                'option_id' => 8,
                'value' => '30',
                'created_at' => '2019-01-22 22:04:58',
                'updated_at' => '2019-01-22 22:04:58',
            ),
            18 => 
            array (
                'id' => 48,
                'course_id' => 15,
                'option_id' => 9,
                'value' => '30',
                'created_at' => '2019-01-22 22:04:58',
                'updated_at' => '2019-01-22 22:04:58',
            ),
            19 => 
            array (
                'id' => 49,
                'course_id' => 55,
                'option_id' => 8,
                'value' => '30',
                'created_at' => '2019-01-24 15:30:00',
                'updated_at' => '2019-01-24 15:30:00',
            ),
            20 => 
            array (
                'id' => 50,
                'course_id' => 55,
                'option_id' => 9,
                'value' => '15',
                'created_at' => '2019-01-24 15:30:00',
                'updated_at' => '2019-01-24 15:30:00',
            ),
            21 => 
            array (
                'id' => 51,
                'course_id' => 56,
                'option_id' => 8,
                'value' => '30',
                'created_at' => '2019-01-24 15:40:50',
                'updated_at' => '2019-01-24 15:40:50',
            ),
            22 => 
            array (
                'id' => 52,
                'course_id' => 56,
                'option_id' => 9,
                'value' => '30',
                'created_at' => '2019-01-24 15:40:50',
                'updated_at' => '2019-01-24 15:40:50',
            ),
            23 => 
            array (
                'id' => 53,
                'course_id' => 21,
                'option_id' => 7,
            'value' => 'rgb(255, 179, 247)',
                'created_at' => '2019-01-24 16:19:38',
                'updated_at' => '2019-01-24 16:19:38',
            ),
            24 => 
            array (
                'id' => 54,
                'course_id' => 21,
                'option_id' => 8,
                'value' => '30',
                'created_at' => '2019-01-24 16:19:38',
                'updated_at' => '2019-01-24 16:19:38',
            ),
            25 => 
            array (
                'id' => 55,
                'course_id' => 21,
                'option_id' => 9,
                'value' => '30',
                'created_at' => '2019-01-24 16:19:38',
                'updated_at' => '2019-01-24 16:19:38',
            ),
            26 => 
            array (
                'id' => 56,
                'course_id' => 21,
                'option_id' => 10,
                'value' => '-15',
                'created_at' => '2019-01-24 16:19:38',
                'updated_at' => '2019-01-24 16:19:38',
            ),
            27 => 
            array (
                'id' => 57,
                'course_id' => 21,
                'option_id' => 11,
                'value' => '-15',
                'created_at' => '2019-01-24 16:19:38',
                'updated_at' => '2019-01-24 16:19:38',
            ),
            28 => 
            array (
                'id' => 58,
                'course_id' => 53,
                'option_id' => 8,
                'value' => '30',
                'created_at' => '2019-01-24 16:36:00',
                'updated_at' => '2019-01-24 16:36:00',
            ),
            29 => 
            array (
                'id' => 59,
                'course_id' => 53,
                'option_id' => 9,
                'value' => '30',
                'created_at' => '2019-01-24 16:36:00',
                'updated_at' => '2019-01-24 16:36:00',
            ),
            30 => 
            array (
                'id' => 60,
                'course_id' => 53,
                'option_id' => 10,
                'value' => '-15',
                'created_at' => '2019-01-24 16:36:00',
                'updated_at' => '2019-01-24 16:36:00',
            ),
            31 => 
            array (
                'id' => 61,
                'course_id' => 53,
                'option_id' => 11,
                'value' => '-15',
                'created_at' => '2019-01-24 16:36:00',
                'updated_at' => '2019-01-24 16:36:00',
            ),
        ));
        
        
    }
}