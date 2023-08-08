<?php

use Illuminate\Database\Seeder;

class UserProfileQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('user_profile_questions')->delete();
        
        DB::table('user_profile_questions')->insert(array (
            0 => 
            array (
                'question_id' => 1,
                'question_text' => 'Role',
                'site_id' => 1,
                'response_type' => 1,
                'display_order' => 1,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'question_id' => 2,
                'question_text' => 'Department',
                'site_id' => 1,
                'response_type' => 1,
                'display_order' => 3,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'question_id' => 3,
                'question_text' => 'Institution',
                'site_id' => 1,
                'response_type' => 1,
                'display_order' => 5,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'question_id' => 4,
                'question_text' => 'test question',
                'site_id' => 1,
                'response_type' => 1,
                'display_order' => 4,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'question_id' => 5,
                'question_text' => 'test question2',
                'site_id' => 1,
                'response_type' => 1,
                'display_order' => 2,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'question_id' => 6,
                'question_text' => 'Year',
                'site_id' => 6,
                'response_type' => 1,
                'display_order' => 1,
                'retire_date' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}