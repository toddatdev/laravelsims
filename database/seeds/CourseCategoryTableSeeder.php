<?php

use Illuminate\Database\Seeder;

class CourseCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('course_category')->delete();
        
        DB::table('course_category')->insert(array (
            0 => 
            array (
                'id' => 1,
                'course_id' => 4,
                'course_category_id' => 6,
                'created_at' => '2018-04-24 16:51:19',
                'updated_at' => '2018-04-24 16:51:19',
            ),
            1 => 
            array (
                'id' => 2,
                'course_id' => 5,
                'course_category_id' => 4,
                'created_at' => '2018-04-24 16:51:36',
                'updated_at' => '2018-04-24 16:51:36',
            ),
            2 => 
            array (
                'id' => 3,
                'course_id' => 1,
                'course_category_id' => 7,
                'created_at' => '2018-05-17 20:29:46',
                'updated_at' => '2018-05-17 20:29:46',
            ),
            3 => 
            array (
                'id' => 4,
                'course_id' => 2,
                'course_category_id' => 8,
                'created_at' => '2018-05-17 20:30:20',
                'updated_at' => '2018-05-17 20:30:20',
            ),
        ));
        
        
    }
}