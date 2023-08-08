<?php

use Illuminate\Database\Seeder;

class CourseEmailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('course_emails')->delete();

        \DB::table('course_emails')->insert(array (
            0 =>
            array (
                'id' => 1,
                'course_id' => 11,
                'email_type_id' => 2,
                'label' => 'Added To Course',
                'subject' => "<p>You've been added to {{course_abbrv}}</p>",
                'body' => '<p>{{first_name}} {{last_name}} you have been added to {{course_abbrv}}.</p>',
                'to_roles' => '28,29',
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
                'created_at' => '2019-10-22 15:20:30',
                'updated_at' => '2019-10-22 15:20:30',
            )
        ));
    }
}
