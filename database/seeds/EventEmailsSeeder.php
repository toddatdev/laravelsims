<?php

use Illuminate\Database\Seeder;

class EventEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('event_emails')->delete();

        \DB::table('event_emails')->insert(array (
            0 =>
            array (
                'id' => 1,
                'event_id' => 153, // Took from last records on EventsTableSeeder
                'email_type_id' => 4,
                'label' => 'Add to Event',
                'subject' => "<p>You've been added to an Event/p>",
                'body' => '<p>You have been added to some Seeder Event.</p>',
                'to_roles' => null,
                'to_other' => null,
                'cc_roles' => null,
                'cc_other' => null,
                'bcc_roles' => null,
                'bcc_other' => null,
                'time_amount' => null,
                'time_type' => null,
                'time_offset' => null,
                'role_id' => null,
                'role_amount' => null,
                'role_offset' => null,
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-10-25 15:20:30',
                'updated_at' => '2019-10-25 15:20:30',
            )
        ));
    }
}
