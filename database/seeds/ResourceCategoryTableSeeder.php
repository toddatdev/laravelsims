<?php

use Illuminate\Database\Seeder;

class ResourceCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('resource_category')->delete();
        
        DB::table('resource_category')->insert(array (
            0 => 
            array (
                'id' => 6,
                'resource_type_id' => 1,
                'abbrv' => 'MET Room',
                'description' => 'Medical Education Theater',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-06-01 15:03:59',
                'updated_at' => '2018-06-01 15:04:25',
            ),
            1 => 
            array (
                'id' => 8,
                'resource_type_id' => 2,
                'abbrv' => 'Adult SIMS',
                'description' => 'Adult Full Scale Simulators',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:36:37',
                'updated_at' => '2018-10-08 20:37:24',
            ),
            2 => 
            array (
                'id' => 10,
                'resource_type_id' => 2,
                'abbrv' => 'Ped SIMS',
                'description' => 'Pediatric Simulators',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:43:42',
                'updated_at' => '2018-10-03 14:43:42',
            ),
            3 => 
            array (
                'id' => 11,
                'resource_type_id' => 2,
                'abbrv' => 'Crash Carts',
                'description' => 'Crash Carts Description',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:52:47',
                'updated_at' => '2018-10-03 14:52:47',
            ),
            4 => 
            array (
                'id' => 12,
                'resource_type_id' => 2,
                'abbrv' => 'Ultrasound Machines',
                'description' => 'Ultrasound Machines Description',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:54:20',
                'updated_at' => '2018-10-03 14:54:20',
            ),
            5 => 
            array (
                'id' => 13,
                'resource_type_id' => 1,
                'abbrv' => 'Classroom',
                'description' => 'Classroom Description',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-03 20:44:55',
                'updated_at' => '2018-10-03 20:44:55',
            ),
            6 => 
            array (
                'id' => 14,
                'resource_type_id' => 2,
                'abbrv' => 'CLT',
                'description' => 'Central Line Trainers',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:39:35',
                'updated_at' => '2018-10-08 20:39:35',
            ),
            7 => 
            array (
                'id' => 15,
                'resource_type_id' => 2,
                'abbrv' => 'Airway Trainer',
                'description' => 'Airway Trainer',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:42:37',
                'updated_at' => '2018-10-08 20:42:37',
            ),
            8 => 
            array (
                'id' => 16,
                'resource_type_id' => 2,
                'abbrv' => 'CPR Trainers',
                'description' => 'CPR Task Trainers',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:44:54',
                'updated_at' => '2018-10-08 20:44:54',
            ),
            9 => 
            array (
                'id' => 17,
                'resource_type_id' => 2,
                'abbrv' => 'Ventilators',
                'description' => 'Ventilators',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:46:38',
                'updated_at' => '2018-10-08 20:46:38',
            ),
            10 => 
            array (
                'id' => 18,
                'resource_type_id' => 1,
                'abbrv' => 'DB Room',
                'description' => 'Debriefing Room',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:47:57',
                'updated_at' => '2018-10-08 20:47:57',
            ),
            11 => 
            array (
                'id' => 19,
                'resource_type_id' => 1,
                'abbrv' => 'ACLS Room',
                'description' => 'ACLS/BLS Room',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:47:00',
                'updated_at' => '2018-10-10 16:52:55',
            ),
            12 => 
            array (
                'id' => 20,
                'resource_type_id' => 1,
                'abbrv' => 'Misc Room',
                'description' => 'Miscellaneous Room',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:54:51',
                'updated_at' => '2018-10-10 16:54:51',
            ),
            13 => 
            array (
                'id' => 21,
                'resource_type_id' => 1,
                'abbrv' => 'Lab',
                'description' => 'Simulation Lab',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:06:18',
                'updated_at' => '2018-10-10 17:22:20',
            ),
            14 => 
            array (
                'id' => 22,
                'resource_type_id' => 1,
                'abbrv' => 'Conf Room',
                'description' => 'Conference Room',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:17:04',
                'updated_at' => '2018-10-10 17:17:04',
            ),
            15 => 
            array (
                'id' => 24,
                'resource_type_id' => 2,
                'abbrv' => 'Patient Transport',
                'description' => 'Patient Transport Device',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:34:27',
                'updated_at' => '2018-10-10 17:34:27',
            ),
            16 => 
            array (
                'id' => 25,
                'resource_type_id' => 1,
                'abbrv' => 'Lab',
                'description' => 'Lab',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:54:33',
                'updated_at' => '2018-10-10 17:54:33',
            ),
            17 => 
            array (
                'id' => 26,
                'resource_type_id' => 1,
                'abbrv' => 'Conference Room',
                'description' => 'Conference Room',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:55:57',
                'updated_at' => '2018-10-10 17:55:57',
            ),
            18 => 
            array (
                'id' => 27,
                'resource_type_id' => 1,
                'abbrv' => 'Classroom',
                'description' => 'Classroom',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:58:52',
                'updated_at' => '2018-10-10 17:58:52',
            ),
            19 => 
            array (
                'id' => 28,
                'resource_type_id' => 1,
                'abbrv' => 'Misc Room',
                'description' => 'Miscellaneous Room',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:00:01',
                'updated_at' => '2018-10-10 18:00:01',
            ),
            20 => 
            array (
                'id' => 29,
                'resource_type_id' => 2,
                'abbrv' => 'Airway Trainer',
                'description' => 'Airway Trainer',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:00:59',
                'updated_at' => '2018-10-10 18:03:35',
            ),
            21 => 
            array (
                'id' => 30,
                'resource_type_id' => 2,
                'abbrv' => 'Defibrillator',
                'description' => 'Defibrillator',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:02:49',
                'updated_at' => '2018-10-10 18:02:49',
            ),
            22 => 
            array (
                'id' => 31,
                'resource_type_id' => 2,
                'abbrv' => 'Endoscopy Trainer',
                'description' => 'Endoscopy Trainer',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:06:01',
                'updated_at' => '2018-10-10 18:06:01',
            ),
            23 => 
            array (
                'id' => 32,
                'resource_type_id' => 1,
                'abbrv' => 'SimRoom',
                'description' => 'Simulation Room',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:01:22',
                'updated_at' => '2018-10-12 16:01:22',
            ),
            24 => 
            array (
                'id' => 33,
                'resource_type_id' => 2,
                'abbrv' => 'Crash Carts',
                'description' => 'Crash Carts',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:17:17',
                'updated_at' => '2018-10-12 16:17:17',
            ),
            25 => 
            array (
                'id' => 34,
                'resource_type_id' => 2,
                'abbrv' => 'Human Sim',
                'description' => 'Human Simulators',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:24:53',
                'updated_at' => '2018-10-12 16:24:53',
            ),
            26 => 
            array (
                'id' => 35,
                'resource_type_id' => 2,
                'abbrv' => 'Laparoscopic Trainer',
                'description' => 'Laparoscopic Trainer',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:28:46',
                'updated_at' => '2018-10-12 16:28:46',
            ),
            27 => 
            array (
                'id' => 36,
                'resource_type_id' => 1,
                'abbrv' => 'Exam Room',
                'description' => 'Examination Room',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:43:34',
                'updated_at' => '2018-10-12 16:43:34',
            ),
            28 => 
            array (
                'id' => 37,
                'resource_type_id' => 1,
                'abbrv' => 'Auditorium',
                'description' => 'Auditorium',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:04:29',
                'updated_at' => '2018-10-12 17:04:29',
            ),
            29 => 
            array (
                'id' => 38,
                'resource_type_id' => 2,
                'abbrv' => 'Task Trainer',
                'description' => 'Task Trainer',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:13:22',
                'updated_at' => '2018-10-12 17:13:22',
            ),
            30 => 
            array (
                'id' => 39,
                'resource_type_id' => 3,
                'abbrv' => 'Sims Specialist',
                'description' => 'Simulation Specialist',
                'site_id' => 6,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:17:16',
                'updated_at' => '2018-10-12 17:17:16',
            ),
            31 => 
            array (
                'id' => 40,
                'resource_type_id' => 3,
                'abbrv' => 'Sims Specialist',
                'description' => 'Simulation Specialist',
                'site_id' => 1,
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:29:43',
                'updated_at' => '2018-10-12 17:29:43',
            ),
        ));
        
        
    }
}