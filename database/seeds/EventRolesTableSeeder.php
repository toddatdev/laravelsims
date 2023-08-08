<?php

use Illuminate\Database\Seeder;

class EventRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('event_roles')->delete();
        
        DB::table('event_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'abbrv' => 'Participant',
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'abbrv' => 'Instructor',
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
            array (
                'id' => 3,
                'abbrv' => 'Operator',
                'created_at' => '2018-05-31 12:00:00',
                'updated_at' => '2018-05-31 12:00:00',
            ),
        ));
        
        
    }
}