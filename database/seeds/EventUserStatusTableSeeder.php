<?php

use Illuminate\Database\Seeder;

class EventUserStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_user_status')->delete();
        
        \DB::table('event_user_status')->insert(array (
            0 => 
            array (
                'id' => 1,
                'status' => 'Enrolled',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'status' => 'Pending',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'status' => 'Waitlist',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'status' => 'Denied',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            4 =>
                array (
                    'id' => 5,
                    'status' => 'Pending Payment',
                    'created_at' => '2021-04-09 12:00:00',
                    'updated_at' => '2021-04-09 12:00:00',
                ),
        ));
        
        
    }
}