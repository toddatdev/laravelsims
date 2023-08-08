<?php

use Illuminate\Database\Seeder;

class EventUserHistoryActionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('event_user_history_actions')->delete();
        
        \DB::table('event_user_history_actions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'action' => 'Denied',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'action' => 'Enrolled',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'action' => 'Moved',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'action' => 'Removed',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'action' => 'Request Access',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'action' => 'Role Change',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'action' => 'Waitlisted',
                'created_at' => '2020-02-05 12:00:00',
                'updated_at' => '2020-02-05 12:00:00',
            ),
            7 =>
                array (
                    'id' => 8,
                    'action' => 'Marked Attended',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
            8 =>
                array (
                    'id' => 9,
                    'action' => 'Unmarked Attended',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
            9 =>
                array (
                    'id' => 10,
                    'action' => 'Payment Approved',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
            10 =>
                array (
                    'id' => 11,
                    'action' => 'Payment Declined',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
            11 =>
                array (
                    'id' => 12,
                    'action' => 'Payment Error',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
            12 =>
                array (
                    'id' => 13,
                    'action' => 'Payment Held for Review',
                    'created_at' => '2020-02-05 12:00:00',
                    'updated_at' => '2020-02-05 12:00:00',
                ),
        ));
        
    }
}