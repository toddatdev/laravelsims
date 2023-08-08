<?php

use Illuminate\Database\Seeder;

class BuildingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('buildings')->delete();
        
        DB::table('buildings')->insert(array (
            0 => 
            array (
                'id' => 7,
                'site_id' => 1,
                'abbrv' => 'MCKEE',
                'name' => 'McKee Place',
                'more_info' => '<p>WISER\'s McKee Place facility is next to the Hilton Garden Inn, which is at the corner of Forbes Ave and McKee Pl. &nbsp;It is caddy corner to the Marathon gas station on Forbes Ave.</p>',
                'map_url' => 'https://www.google.com/maps/place/Professional+Building,+230+McKee+Pl,+Pittsburgh,+PA+15213/@40.4389795,-79.961401,17z/data=!3m1!4b1!4m5!3m4!1s0x8834f180c59b0465:0xc21ef6670ffe986b!8m2!3d40.4389795!4d-79.9592123',
                'address' => '230 McKee Pl',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15213',
                'display_order' => 1,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-21 12:03:30',
                'updated_at' => '2018-03-21 14:44:59',
                'deleted_at' => '2018-02-21 12:03:30',
            ),
            1 => 
            array (
                'id' => 8,
                'site_id' => 1,
                'abbrv' => 'PASS',
                'name' => 'UPMC Passavant',
                'more_info' => '<p>This is up in McCandless off of McNight Rd.&nbsp;</p>',
                'map_url' => 'https://www.google.com/maps/place/UPMC+Passavant+Hospital+-+McCandless/@40.5391918,-80.0109096,12.6z/data=!4m8!1m2!2m1!1spassavant+hospital!3m4!1s0x88348b846542d5f9:0xc3628a48b87cd905!8m2!3d40.5731434!4d-80.0138667',
                'address' => '9100 Babcock Blvd',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15237',
                'display_order' => 2,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-21 12:04:39',
                'updated_at' => '2018-03-21 14:46:29',
                'deleted_at' => '2018-02-21 12:04:39',
            ),
            2 => 
            array (
                'id' => 9,
                'site_id' => 1,
                'abbrv' => 'MKS',
                'name' => 'UPMC McKeesport',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/UPMC+McKeesport/@40.3486298,-79.8463792,15.4z/data=!4m5!3m4!1s0x8834ef5f9e8789b7:0xe5c59bf9fc032b1f!8m2!3d40.3514348!4d-79.8496073',
                'address' => '1500 5th Ave',
                'city' => 'McKeesport',
                'state' => 'PA',
                'postal_code' => '15132',
                'display_order' => 3,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-21 21:19:20',
                'updated_at' => '2018-02-22 17:29:50',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 11,
                'site_id' => 1,
                'abbrv' => 'SHY',
                'name' => 'UPMC Shadyside',
                'more_info' => '<p>This is the main <strong><span style="color: green;">Shadyside Hospital</span></strong>. The locations for this are in different areas of the campus.&nbsp;</p>',
                'map_url' => 'https://www.google.com/maps/place/UPMC+Shadyside/@40.4546998,-79.9416601,17z/data=!3m1!4b1!4m5!3m4!1s0x8834f215dc8efbdd:0xc0293ebdad74c6dd!8m2!3d40.4546998!4d-79.9394661',
                'address' => '5230 Centre Ave',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15232',
                'display_order' => 4,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-21 21:36:26',
                'updated_at' => '2018-03-21 14:47:01',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 12,
                'site_id' => 1,
                'abbrv' => 'CHP',
                'name' => 'Children\'s Hospital of Pittsburgh of UPMC',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/Children\'s+Hospital+of+Pittsburgh+of+UPMC/@40.4671269,-79.9554901,17.32z/data=!4m5!3m4!1s0x88346201dbf2928b:0x913355e72bccdc3f!8m2!3d40.4671228!4d-79.9532961',
                'address' => '4401 Penn Ave',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15224',
                'display_order' => 5,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-22 01:23:12',
                'updated_at' => '2018-02-26 21:49:28',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 14,
                'site_id' => 1,
                'abbrv' => 'MWH',
                'name' => 'Magee Women\'s Hospital of UPMC',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/Magee-Women\'s+Hospital+of+UPMC/@40.4369474,-79.9629357,17z/data=!3m1!4b1!4m5!3m4!1s0x883462a7a107bf51:0xa9f526c489a7f9c4!8m2!3d40.4369433!4d-79.960747',
                'address' => '300 Halket St',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15213',
                'display_order' => 6,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-22 02:57:33',
                'updated_at' => '2018-02-24 04:02:32',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 15,
                'site_id' => 1,
                'abbrv' => 'PRES',
                'name' => 'UPMC Presbyterian',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/UPMC+Presbyterian/@40.4426924,-79.962809,17z/data=!3m1!4b1!4m5!3m4!1s0x8834f2260336988f:0xbbaee7f8232673f4!8m2!3d40.4426883!4d-79.9606203',
                'address' => '200 Lothrop St,',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15213',
                'display_order' => 8,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-22 02:58:48',
                'updated_at' => '2018-03-21 14:46:06',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 16,
                'site_id' => 1,
                'abbrv' => 'EAST-A',
                'name' => 'UPMC East - Mosside Annex.',
                'more_info' => '<p>This is the Annex building, rather than the Main Hospital.&nbsp; It is the white building further down Mosside Blvd.</p>',
                'map_url' => 'https://www.google.com/maps/place/Jennifer+L.+Boyd,+MPT/@40.4360948,-79.7583667,300m/data=!3m2!1e3!4b1!4m5!3m4!1s0x8834ea16db852743:0xc71ed6c672b0ded4!8m2!3d40.4360948!4d-79.7578182',
                'address' => '2735 Mosside Blvd',
                'city' => 'Monroeville',
                'state' => 'PA',
                'postal_code' => '15146',
                'display_order' => 9,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-22 02:59:51',
                'updated_at' => '2018-02-23 18:54:29',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 18,
                'site_id' => 1,
                'abbrv' => 'MER',
                'name' => 'UPMC Mercy',
                'more_info' => '<p>We don\'t have a dedicated space here yet. Our classes are located throughout the hospital. Please look for directions in the comments of your individual class.&nbsp;</p>',
                'map_url' => 'https://www.google.com/maps/place/UPMC+Mercy/@40.4360891,-79.9875607,17z/data=!3m1!4b1!4m5!3m4!1s0x8834f1676015ebb1:0x7269a65ed7a93ffe!8m2!3d40.436085!4d-79.985372',
                'address' => '1400 Locust St',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15219',
                'display_order' => 10,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-22 03:02:53',
                'updated_at' => '2018-03-21 14:48:03',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 22,
                'site_id' => 1,
                'abbrv' => 'EAST',
                'name' => 'UPMC East',
                'more_info' => '<p>This is&nbsp;the main hospital</p>',
                'map_url' => 'https://www.google.com/maps/place/UPMC+East/@40.436464,-79.759898,15z/data=!4m5!3m4!1s0x0:0x46309d15bb658193!8m2!3d40.436464!4d-79.759898',
                'address' => '2775 Mosside Blvd',
                'city' => 'Monroeville',
                'state' => 'PA',
                'postal_code' => '15146',
                'display_order' => 11,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-23 15:20:14',
                'updated_at' => '2018-02-27 18:14:47',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 24,
                'site_id' => 1,
                'abbrv' => 'Victoria Hall',
                'name' => 'SON',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/3400+Victoria+St,+Pittsburgh,+PA+15213/@40.4411574,-79.9634765,17z/data=!3m1!4b1!4m5!3m4!1s0x8834f22a7cc4ba77:0xd2103794187d0bc5!8m2!3d40.4411533!4d-79.9612878',
                'address' => '3400 Victoria St',
                'city' => 'Pittsbugh',
                'state' => 'PA',
                'postal_code' => '15213',
                'display_order' => 12,
                'retire_date' => '2018-12-14 16:55:55',
                'timezone' => 'America/New_York',
                'created_at' => '2018-02-26 16:55:55',
                'updated_at' => '2018-02-27 19:15:56',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 25,
                'site_id' => 1,
                'abbrv' => 'MARG',
                'name' => 'UPMC St. Margaret',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/UPMC+St.+Margaret/@40.4894589,-79.8981275,17z/data=!3m1!4b1!4m5!3m4!1s0x8834ed1338fad917:0x186dc8ccb61f2ce6!8m2!3d40.4894589!4d-79.8959388',
                'address' => '815 Freeport Rd',
                'city' => 'Pittsburgh',
                'state' => 'PA',
                'postal_code' => '15215',
                'display_order' => 13,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-03-21 14:53:07',
                'updated_at' => '2018-03-21 14:53:23',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 26,
                'site_id' => 1,
                'abbrv' => 'OFFSITE',
                'name' => 'Offsite Location',
                'more_info' => '<p>This is not part of WISER\'s standard locations. Details about the exact location will be in the comments or notes about the event.&nbsp;</p>',
                'map_url' => NULL,
                'address' => NULL,
                'city' => NULL,
                'state' => NULL,
                'postal_code' => NULL,
                'display_order' => 14,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-03-21 14:55:44',
                'updated_at' => '2018-03-21 14:55:58',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 27,
                'site_id' => 1,
                'abbrv' => 'SHY-P',
                'name' => 'UPMC Shadyside Preservation Hall',
                'more_info' => '<p>This is the Preservation Hall Building by the Emergency Department Ambulance Bays.&nbsp;</p>',
                'map_url' => 'https://www.google.com/maps/place/40°27\'14.9%22N+79°56\'21.3%22W/@40.4541432,-79.9395136,20z/data=!4m6!3m5!1s0x0:0x0!7e2!8m2!3d40.4541439!4d-79.93924',
                'address' => NULL,
                'city' => NULL,
                'state' => NULL,
                'postal_code' => NULL,
                'display_order' => 15,
                'retire_date' => null,
                'timezone' => 'America/New_York',
                'created_at' => '2018-03-21 15:02:04',
                'updated_at' => '2018-03-21 15:02:04',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 28,
                'site_id' => 6,
                'abbrv' => 'MEB',
                'name' => 'John A Burns School of Medicine Medical Education Building',
                'more_info' => '<p><strong>Parking:</strong></p>
<ul>
<li>Lot C chain linked public parking lot located on the Ewa (West) side, next to the Kakaako campus.
<ul>
<li>Stalls WITH number - place $5 in the payment box located inside Lot C, near the exit.</li>
<li>Stalls WITHOUT numbers - purchase a $5 voucher from SimTiki and place it on the dash of your car. Call Kris Hara 808-692-1096 and someone will meet you in front of the Medical Education Building. Exact change is appreciated.</li>
</ul>
</li>
<li>Metered street parking is available on Ilalo Street, Cooke Street and other streets 2 blocks from the JABSOM campus.</li>
<li>Do NOT park in Kakaako Park stalls, you may be towed.</li>
</ul>',
                'map_url' => NULL,
                'address' => '651 Ilalo Street',
                'city' => 'Honolulu',
                'state' => 'HI',
                'postal_code' => '96813-5534',
                'display_order' => 1,
                'retire_date' => null,
                'timezone' => 'Pacific/Honolulu',
                'created_at' => '2018-03-21 17:22:14',
                'updated_at' => '2018-03-21 17:59:41',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 29,
                'site_id' => 6,
                'abbrv' => 'Queens',
                'name' => 'Queens Medical Center',
                'more_info' => NULL,
                'map_url' => 'https://www.google.com/maps/place/1301+Punchbowl+St,+Honolulu,+HI+96813/@21.3084698,-157.8560468,17z/data=!3m1!4b1!4m5!3m4!1s0x7c006dde52658b3d:0x8eb578e0af43e4a2!8m2!3d21.3084698!4d-157.8538581',
                'address' => '1301 Punchbowl Street',
                'city' => 'Honolulu',
                'state' => 'HI',
                'postal_code' => '96813',
                'display_order' => 2,
                'retire_date' => null,
                'timezone' => 'Pacific/Honolulu',
                'created_at' => '2018-03-21 17:35:23',
                'updated_at' => '2018-03-21 17:35:23',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 30,
                'site_id' => 6,
                'abbrv' => 'Tripler',
                'name' => 'Tripler Army Medical Center',
                'more_info' => '<p>Tripler is the pink palace on the hill. The off-ramp leads to Puuloa Rd/Jarrett White, parking terraces and the ER are on your left. A parking lot for patients/visitors is located near the G Wing entrance. Additional parking is available in the hospitals parking structure across the street from the Mountainside Entrance on Patterson Road.</p>
<p>Please ask for assistance from Information Receptionists if you are unsure of the route of your destination or have any other questions about Tripler and its services.</p>',
                'map_url' => NULL,
                'address' => '1 Jarrett White Road',
                'city' => 'Honolulu',
                'state' => 'HI',
                'postal_code' => '96859-5000',
                'display_order' => 3,
                'retire_date' => null,
                'timezone' => 'Pacific/Honolulu',
                'created_at' => '2018-03-21 17:36:34',
                'updated_at' => '2018-03-21 17:38:01',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}