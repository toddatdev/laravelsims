<?php

use Illuminate\Database\Seeder;

class EmailTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('email_types')->delete();

        \DB::table('email_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Confirm New Account',
                'type' => 1, // site
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Add to Course',
                'type' => 2, // course
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Remove from Course',
                'type' => 2, // course
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Add to Event',
                'type' => 3, // event
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Remove from Event',
                'type' => 3, // event
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Send Manually',
                'type' => 1, // site
                'created_at' => '2019-08-20 10:15:00',
                'updated_at' => '2019-08-20 10:15:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Password Reset',
                'type' => 1, // site
                'created_at' => '2019-08-22 10:15:00',
                'updated_at' => '2019-08-22 10:15:00'
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Automatically Send',
                'type' => 3, // event
                'created_at' => '2019-08-22 10:15:00',
                'updated_at' => '2019-08-22 10:15:00'
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Event Send Manually',
                'type' => 3, // event
                'created_at' => '2019-11-13 11:15:00',
                'updated_at' => '2019-11-13 11:15:00'
            ),
            9 =>
                array (
                    'id' => 10,
                    'name' => 'Send Manually',
                    'type' => 2, // course
                    'created_at' => '2021-06-08 11:15:00',
                    'updated_at' => '2021-06-08 11:15:00'
                ),
        ));
    }
}