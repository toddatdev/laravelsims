<?php

use Illuminate\Database\Seeder;

class SiteEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('site_emails')->delete();

        \DB::table('site_emails')->insert(array (
            0 =>
            array (
                'id' => 1,
                'site_id' => 1,
                'email_type_id' => 1,
                'label' => 'New User is Confirmed',
                'subject' => 'Thank You For Joining Our Site',
                'body' => '<p>Hello&nbsp;{{first_name}}&nbsp;{{last_name}},</p><p>Thank you for signing up to join our site!</p><p>We are please to have your business.</p>',
                'created_by' => 1,
                'last_edited_by' => 1,
                'created_at' => '2019-08-09 14:45:00',
                'updated_at' => '2019-08-09 14:45:00',
            )
        ));
    }
}