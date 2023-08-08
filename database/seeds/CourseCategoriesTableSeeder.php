<?php

use Illuminate\Database\Seeder;

class CourseCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('course_categories')->delete();
        
        DB::table('course_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbrv' => 'ANES',
                'description' => 'Anesthesia',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:49:42',
                'updated_at' => '2018-04-24 16:49:42',
            ),
            1 => 
            array (
                'id' => 2,
                'abbrv' => 'CCM',
                'description' => 'Critical Care Medicine',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:49:58',
                'updated_at' => '2018-04-24 16:49:58',
            ),
            2 => 
            array (
                'id' => 3,
                'abbrv' => 'Surgery',
                'description' => 'Surgery',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:50:17',
                'updated_at' => '2018-04-24 16:50:17',
            ),
            3 => 
            array (
                'id' => 4,
                'abbrv' => 'EM',
                'description' => 'Emergency Medicine',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:50:37',
                'updated_at' => '2018-04-24 16:50:37',
            ),
            4 => 
            array (
                'id' => 5,
                'abbrv' => 'IM',
                'description' => 'Internal Medicine',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:50:52',
                'updated_at' => '2018-04-24 16:50:52',
            ),
            5 => 
            array (
                'id' => 6,
                'abbrv' => 'MISC',
                'description' => 'Miscellaneous',
                'course_category_group_id' => 1,
                'site_id' => 6,
                'created_at' => '2018-04-24 16:51:16',
                'updated_at' => '2018-04-24 16:51:16',
            ),
            6 => 
            array (
                'id' => 7,
                'abbrv' => 'Anes',
                'description' => 'Anesthesiology Department',
                'course_category_group_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-05-17 20:28:21',
                'updated_at' => '2018-05-17 20:28:21',
            ),
            7 => 
            array (
                'id' => 8,
                'abbrv' => 'EM',
                'description' => 'Emergency Medicine Department',
                'course_category_group_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-05-17 20:28:41',
                'updated_at' => '2018-05-17 20:28:41',
            ),
            8 => 
            array (
                'id' => 9,
                'abbrv' => 'CCM',
                'description' => 'Critical Care Medicine Department',
                'course_category_group_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-05-17 20:29:03',
                'updated_at' => '2018-05-17 20:29:03',
            ),
            9 => 
            array (
                'id' => 10,
                'abbrv' => 'SOM',
                'description' => 'School Of Medicine',
                'course_category_group_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-05-17 20:29:20',
                'updated_at' => '2018-05-17 20:29:20',
            ),
            10 => 
            array (
                'id' => 11,
                'abbrv' => 'SON',
                'description' => 'School of Nursing',
                'course_category_group_id' => 2,
                'site_id' => 1,
                'created_at' => '2018-05-17 20:29:31',
                'updated_at' => '2018-05-17 20:29:31',
            ),
        ));
        
        
    }
}