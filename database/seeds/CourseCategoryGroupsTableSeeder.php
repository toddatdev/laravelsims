<?php

use Illuminate\Database\Seeder;

class CourseCategoryGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('course_category_groups')->delete();
        
        DB::table('course_category_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbrv' => 'Admin Dept',
                'description' => 'Administrative Department',
                'site_id' => 6,
                'created_at' => '2018-04-24 16:49:29',
                'updated_at' => '2018-04-24 16:49:29',
            ),
            1 => 
            array (
                'id' => 2,
                'abbrv' => 'Admin',
                'description' => 'Course Administrative Responsibility',
                'site_id' => 1,
                'created_at' => '2018-05-17 20:28:04',
                'updated_at' => '2018-05-17 20:28:04',
            ),
            array (
                'id' => 3,
                'abbrv' => 'Course Catalog Filter',
                'description' => 'Filter for Course Catalog',
                'site_id' => 1,
                'created_at' => '2018-05-17 20:28:04',
                'updated_at' => '2018-05-17 20:28:04',
            ),
        ));
        
        
    }
}