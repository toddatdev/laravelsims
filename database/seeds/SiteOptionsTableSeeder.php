<?php

use Illuminate\Database\Seeder;

class SiteOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('site_options')->delete();
        
        \DB::table('site_options')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Banner Background Color',
            'description' => 'The background color of the top public (frontend) naviation banner on the site.  Could be any valid CSS color, but <b>should</b> be an rgba value for clarity, e.g. <b>rgba(30,130,210, .7)</b>',
                'client_managed' => 0,
                'created_at' => '2018-04-16 17:04:29',
                'updated_at' => '2018-04-16 17:04:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Banner Font Color',
            'description' => 'The font color of the top public (frontend) naviation banner on the site.  Could be any valid CSS color, but <b>should</b> be an rgba value for clarity, e.g. <b>rgba(30,130,210, .7)</b>',
                'client_managed' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Allow Open User Registration',
                'description' => 'Allow users to create accounts on their own on the web site. An uppercase Y allows this, anything else dissallows it.',
                'client_managed' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Standard User',
                'description' => 'This is the roles.id of the "standard user" for this site. This is the role that has NO permissions and is what a new user is assgined to automatically when they create their own account.',
                'client_managed' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Google Analytics Tracking Code',
                'description' => 'This is the code tht gets put into the Google Analytics tracking script. It needs to be created ahead of time on analytics.google.com and only needs to be the alphanumeric code, e.g. UA-118665145-1',
                'client_managed' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Business Begin Hour',
            'description' => '24 Hour Format (two digits with leading 0 for less than 10)',
                'client_managed' => 0,
                'created_at' => '2018-09-12 00:00:00',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Business End Hour',
            'description' => '24 Hour Format (two digits with leading 0 for less than 10)',
                'client_managed' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Multi-lingual',
            'description' => 'The site is multi-lingual and will show the Language menu in the header. "Y" if it is.',
                'client_managed' => 0,
                'created_at' => '2018-10-14 14:00:00',
                'updated_at' => '2018-10-14 14:00:00',
            ),
            8 =>
                array (
                    'id' => 9,
                    'name' => 'Authorize.NET Login ID',
                    'description' => 'Authorize.NET Login ID',
                    'client_managed' => 0,
                    'created_at' => '2021-04-09 00:00:00',
                    'updated_at' => '2021-04-09 00:00:00',
                ),
            9 =>
                array (
                    'id' => 10,
                    'name' => 'Authorize.NET Transaction Key',
                    'description' => 'Authorize.NET Transaction Key',
                    'client_managed' => 0,
                    'created_at' => '2021-04-09 00:00:00',
                    'updated_at' => '2021-04-09 00:00:00',
                ),
            10 =>
                array (
                    'id' => 11,
                    'name' => 'Payment Policy',
                    'description' => 'Allows sites to add text (such as a cancellation policy) to appear on Payment page.',
                    'client_managed' => 0,
                    'created_at' => '2021-04-09 00:00:00',
                    'updated_at' => '2021-04-09 00:00:00',
                ),
            11 =>
                array (
                    'id' => 12,
                    'name' => 'Authorize.NET Signature Key',
                    'description' => 'Authorize.NET Signature Key',
                    'client_managed' => 0,
                    'created_at' => '2021-04-30 00:00:00',
                    'updated_at' => '2021-04-30 00:00:00',
                ),
        ));
        
        
    }
}