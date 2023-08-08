<?php

use Illuminate\Database\Seeder;

class EventStatusTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_status_types')->delete();
        
        \DB::table('event_status_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_id' => 1,
                'description' => 'Complete',
                'icon' => 'fas fa-flag',
                'html_color' => '#339933',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'site_id' => 1,
                'description' => 'In Progress',
                'icon' => 'fas fa-flag',
                'html_color' => '#ffcc00',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'site_id' => 1,
                'description' => 'Not Started',
                'icon' => 'fas fa-flag',
                'html_color' => ' #ff0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'site_id' => 11,
                'description' => 'Complete',
                'icon' => 'fas fa-flag',
                'html_color' => '#339933',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'site_id' => 11,
                'description' => 'In Progress',
                'icon' => 'fas fa-flag',
                'html_color' => '#ffcc00',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'site_id' => 11,
                'description' => 'Not Started',
                'icon' => 'fas fa-flag',
                'html_color' => ' #ff0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'site_id' => 4,
                'description' => 'Complete',
                'icon' => 'fas fa-flag',
                'html_color' => '#339933',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'site_id' => 4,
                'description' => 'In Progress',
                'icon' => 'fas fa-flag',
                'html_color' => '#ffcc00',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'site_id' => 4,
                'description' => 'Not Started',
                'icon' => 'fas fa-flag',
                'html_color' => ' #ff0000',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}