<?php

use Illuminate\Database\Seeder;

class ResourceSubCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('resource_sub_category')->delete();
        
        DB::table('resource_sub_category')->insert(array (
            0 => 
            array (
                'id' => 4,
                'resource_category_id' => 8,
                'abbrv' => '3G',
                'description' => 'Laerdal 3G',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:37:40',
                'updated_at' => '2018-10-03 14:37:40',
            ),
            1 => 
            array (
                'id' => 5,
                'resource_category_id' => 8,
                'abbrv' => 'SimMan Classic',
                'description' => 'Laerdal SimMan Classic',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:39:34',
                'updated_at' => '2018-10-03 14:39:34',
            ),
            2 => 
            array (
                'id' => 6,
                'resource_category_id' => 8,
                'abbrv' => 'SimMan Essential',
                'description' => 'Laerdal SimMan Essential',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:40:08',
                'updated_at' => '2018-10-03 14:40:08',
            ),
            3 => 
            array (
                'id' => 7,
                'resource_category_id' => 8,
                'abbrv' => 'ALS',
                'description' => 'Advanced Life Support Manikin',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:42:50',
                'updated_at' => '2018-10-03 14:42:50',
            ),
            4 => 
            array (
                'id' => 8,
                'resource_category_id' => 8,
                'abbrv' => 'Noelle',
                'description' => 'Noelle Simulator',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:43:10',
                'updated_at' => '2018-10-03 14:43:10',
            ),
            5 => 
            array (
                'id' => 9,
                'resource_category_id' => 10,
                'abbrv' => 'SimBaby',
                'description' => 'Laerdal SimBaby',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:49:55',
                'updated_at' => '2018-10-03 14:49:55',
            ),
            6 => 
            array (
                'id' => 10,
                'resource_category_id' => 10,
                'abbrv' => 'SimNewB',
                'description' => 'Laerdal SimNewB',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:50:48',
                'updated_at' => '2018-10-03 14:50:48',
            ),
            7 => 
            array (
                'id' => 11,
                'resource_category_id' => 10,
                'abbrv' => 'SimJunior',
                'description' => 'Laerdal SimJunior',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:51:09',
                'updated_at' => '2018-10-03 14:51:09',
            ),
            8 => 
            array (
                'id' => 12,
                'resource_category_id' => 10,
                'abbrv' => 'Premie Anne',
                'description' => 'Laerdal Premie Anne',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:51:49',
                'updated_at' => '2018-10-03 14:51:49',
            ),
            9 => 
            array (
                'id' => 13,
                'resource_category_id' => 11,
                'abbrv' => 'Adult',
                'description' => 'Adult Crash Cart',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:53:05',
                'updated_at' => '2018-10-03 14:53:05',
            ),
            10 => 
            array (
                'id' => 14,
                'resource_category_id' => 11,
                'abbrv' => 'Pediatric',
                'description' => 'Pediatric Crash Cart',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:53:23',
                'updated_at' => '2018-10-03 14:53:23',
            ),
            11 => 
            array (
                'id' => 15,
                'resource_category_id' => 12,
                'abbrv' => 'Vimedex',
                'description' => 'Vimedex Description',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:54:49',
                'updated_at' => '2018-10-03 14:54:49',
            ),
            12 => 
            array (
                'id' => 16,
                'resource_category_id' => 12,
                'abbrv' => 'SonoSite',
                'description' => 'SonoSite Description',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 14:55:16',
                'updated_at' => '2018-10-03 14:55:16',
            ),
            13 => 
            array (
                'id' => 17,
                'resource_category_id' => 6,
                'abbrv' => 'Small',
                'description' => 'Under 10',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 20:43:18',
                'updated_at' => '2018-10-03 20:43:18',
            ),
            14 => 
            array (
                'id' => 18,
                'resource_category_id' => 6,
                'abbrv' => 'Medium',
                'description' => '11-25',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 20:43:39',
                'updated_at' => '2018-10-03 20:43:39',
            ),
            15 => 
            array (
                'id' => 19,
                'resource_category_id' => 6,
                'abbrv' => 'Large',
                'description' => 'Over 25',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 20:43:56',
                'updated_at' => '2018-10-03 20:43:56',
            ),
            16 => 
            array (
                'id' => 26,
                'resource_category_id' => 13,
                'abbrv' => 'Large',
                'description' => 'More than 25',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 21:30:57',
                'updated_at' => '2018-10-03 21:37:28',
            ),
            17 => 
            array (
                'id' => 27,
                'resource_category_id' => 13,
                'abbrv' => 'Medium',
                'description' => 'Between 10 and 25',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 21:31:49',
                'updated_at' => '2018-10-03 21:31:49',
            ),
            18 => 
            array (
                'id' => 28,
                'resource_category_id' => 13,
                'abbrv' => 'Small',
                'description' => 'Less than 10',
                'retire_date' => NULL,
                'created_at' => '2018-10-03 21:37:53',
                'updated_at' => '2018-10-03 21:37:53',
            ),
            19 => 
            array (
                'id' => 29,
                'resource_category_id' => 14,
                'abbrv' => 'Non Ultrasound Capable',
                'description' => 'Device not equipped with ultrasound',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:40:23',
                'updated_at' => '2018-10-08 20:40:23',
            ),
            20 => 
            array (
                'id' => 30,
                'resource_category_id' => 14,
                'abbrv' => 'Ultrasound Capable',
                'description' => 'Has ultrasound capability',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:41:57',
                'updated_at' => '2018-10-08 20:41:57',
            ),
            21 => 
            array (
                'id' => 31,
                'resource_category_id' => 15,
                'abbrv' => 'Laerdal Airway Trainer',
                'description' => 'Laerdal Airway Management Trainer',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:43:15',
                'updated_at' => '2018-10-08 20:43:15',
            ),
            22 => 
            array (
                'id' => 32,
                'resource_category_id' => 15,
                'abbrv' => 'Ambu Airway Trainer',
                'description' => 'Ambu Airway Trainer',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:43:42',
                'updated_at' => '2018-10-08 20:43:42',
            ),
            23 => 
            array (
                'id' => 33,
                'resource_category_id' => 16,
                'abbrv' => 'Resusci Anne',
                'description' => 'Resusci Anne',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:45:18',
                'updated_at' => '2018-10-08 20:45:18',
            ),
            24 => 
            array (
                'id' => 34,
                'resource_category_id' => 16,
                'abbrv' => 'Resusci Anne QCPR',
                'description' => 'Resusci Anne QCPR',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:45:33',
                'updated_at' => '2018-10-08 20:45:33',
            ),
            25 => 
            array (
                'id' => 35,
                'resource_category_id' => 16,
                'abbrv' => 'Little Junior QCPR',
                'description' => 'Little Junior QCPR',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:45:55',
                'updated_at' => '2018-10-08 20:45:55',
            ),
            26 => 
            array (
                'id' => 36,
                'resource_category_id' => 17,
                'abbrv' => 'GE',
                'description' => 'General Electric',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:46:50',
                'updated_at' => '2018-10-08 20:46:50',
            ),
            27 => 
            array (
                'id' => 37,
                'resource_category_id' => 17,
                'abbrv' => 'Siemens',
                'description' => 'Siemens',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:47:03',
                'updated_at' => '2018-10-08 20:47:03',
            ),
            28 => 
            array (
                'id' => 38,
                'resource_category_id' => 17,
                'abbrv' => 'Puritan Bennett',
                'description' => 'Puritan Bennett',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:47:21',
                'updated_at' => '2018-10-08 20:47:21',
            ),
            29 => 
            array (
                'id' => 39,
                'resource_category_id' => 18,
                'abbrv' => 'Small',
                'description' => 'Up to 10',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:48:12',
                'updated_at' => '2018-10-08 20:48:12',
            ),
            30 => 
            array (
                'id' => 40,
                'resource_category_id' => 18,
                'abbrv' => 'Medium',
                'description' => '10 - 20 students',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:48:36',
                'updated_at' => '2018-10-08 20:48:36',
            ),
            31 => 
            array (
                'id' => 41,
                'resource_category_id' => 18,
                'abbrv' => 'Large',
                'description' => '25 Plus',
                'retire_date' => NULL,
                'created_at' => '2018-10-08 20:48:55',
                'updated_at' => '2018-10-08 20:48:55',
            ),
            32 => 
            array (
                'id' => 42,
                'resource_category_id' => 19,
                'abbrv' => 'Small',
                'description' => 'Under 5',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:47:33',
                'updated_at' => '2018-10-10 16:47:33',
            ),
            33 => 
            array (
                'id' => 43,
                'resource_category_id' => 20,
                'abbrv' => 'Small',
                'description' => '0-10',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:55:05',
                'updated_at' => '2018-10-10 16:55:05',
            ),
            34 => 
            array (
                'id' => 44,
                'resource_category_id' => 20,
                'abbrv' => 'Medium',
                'description' => '11-20',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:55:18',
                'updated_at' => '2018-10-10 16:55:18',
            ),
            35 => 
            array (
                'id' => 45,
                'resource_category_id' => 20,
                'abbrv' => 'Large',
                'description' => '21-40',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 16:55:32',
                'updated_at' => '2018-10-10 16:55:32',
            ),
            36 => 
            array (
                'id' => 46,
                'resource_category_id' => 22,
                'abbrv' => 'Small',
                'description' => '0-10',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:17:25',
                'updated_at' => '2018-10-10 17:17:25',
            ),
            37 => 
            array (
                'id' => 47,
                'resource_category_id' => 22,
                'abbrv' => 'Medium',
                'description' => '11-20',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:17:48',
                'updated_at' => '2018-10-10 17:17:48',
            ),
            38 => 
            array (
                'id' => 48,
                'resource_category_id' => 21,
                'abbrv' => 'MPL',
                'description' => 'Medical Procedures Lab',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:22:38',
                'updated_at' => '2018-10-10 17:22:38',
            ),
            39 => 
            array (
                'id' => 49,
                'resource_category_id' => 21,
                'abbrv' => 'Sim Lab',
                'description' => 'Simulation Lab',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:23:10',
                'updated_at' => '2018-10-10 17:23:10',
            ),
            40 => 
            array (
                'id' => 50,
                'resource_category_id' => 24,
                'abbrv' => 'Stretcher',
                'description' => 'Transport Stretcher',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:35:02',
                'updated_at' => '2018-10-10 17:35:02',
            ),
            41 => 
            array (
                'id' => 51,
                'resource_category_id' => 25,
                'abbrv' => 'Computer Lab',
                'description' => 'Computer Lab',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:54:54',
                'updated_at' => '2018-10-10 17:54:54',
            ),
            42 => 
            array (
                'id' => 52,
                'resource_category_id' => 26,
                'abbrv' => 'Small',
                'description' => '0-10 People',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:56:16',
                'updated_at' => '2018-10-10 17:56:16',
            ),
            43 => 
            array (
                'id' => 53,
                'resource_category_id' => 26,
                'abbrv' => 'Medium',
                'description' => '11-20 People',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:56:30',
                'updated_at' => '2018-10-10 17:56:30',
            ),
            44 => 
            array (
                'id' => 54,
                'resource_category_id' => 26,
                'abbrv' => 'Large',
                'description' => '21-50 people',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:56:42',
                'updated_at' => '2018-10-10 17:56:42',
            ),
            45 => 
            array (
                'id' => 55,
                'resource_category_id' => 27,
                'abbrv' => 'Small',
                'description' => '0-10 People',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:59:06',
                'updated_at' => '2018-10-10 17:59:06',
            ),
            46 => 
            array (
                'id' => 56,
                'resource_category_id' => 27,
                'abbrv' => 'Medium',
                'description' => '11-20',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:59:16',
                'updated_at' => '2018-10-10 17:59:16',
            ),
            47 => 
            array (
                'id' => 57,
                'resource_category_id' => 27,
                'abbrv' => 'Large',
                'description' => '21-50 people',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 17:59:25',
                'updated_at' => '2018-10-10 17:59:25',
            ),
            48 => 
            array (
                'id' => 58,
                'resource_category_id' => 28,
                'abbrv' => 'Lobby',
                'description' => 'Lobby Meeting Place',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:00:11',
                'updated_at' => '2018-10-10 18:00:11',
            ),
            49 => 
            array (
                'id' => 59,
                'resource_category_id' => 28,
                'abbrv' => 'Control',
                'description' => 'Control Room',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:00:29',
                'updated_at' => '2018-10-10 18:00:29',
            ),
            50 => 
            array (
                'id' => 60,
                'resource_category_id' => 29,
                'abbrv' => '+Laryngospasm +NG',
                'description' => '+Laryngospasm +NG',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:01:37',
                'updated_at' => '2018-10-10 18:05:35',
            ),
            51 => 
            array (
                'id' => 61,
                'resource_category_id' => 31,
                'abbrv' => 'Bronchoscopy',
                'description' => 'Adult/Ped Bronchoscopy',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:06:40',
                'updated_at' => '2018-10-10 18:06:40',
            ),
            52 => 
            array (
                'id' => 62,
                'resource_category_id' => 31,
                'abbrv' => 'Colonoscopy/EGD',
                'description' => 'Colonoscopy/EGD',
                'retire_date' => NULL,
                'created_at' => '2018-10-10 18:07:30',
                'updated_at' => '2018-10-10 18:07:30',
            ),
            53 => 
            array (
                'id' => 63,
                'resource_category_id' => 32,
                'abbrv' => 'Small',
                'description' => '1-10 People',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:06:07',
                'updated_at' => '2018-10-12 16:06:07',
            ),
            54 => 
            array (
                'id' => 64,
                'resource_category_id' => 32,
                'abbrv' => 'Medium',
                'description' => '10 - 20 people',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:06:24',
                'updated_at' => '2018-10-12 16:06:24',
            ),
            55 => 
            array (
                'id' => 65,
                'resource_category_id' => 32,
                'abbrv' => 'Large',
                'description' => '20-50 people',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:06:40',
                'updated_at' => '2018-10-12 16:06:40',
            ),
            56 => 
            array (
                'id' => 66,
                'resource_category_id' => 30,
                'abbrv' => 'Hands Free',
                'description' => 'Hands Free',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:13:22',
                'updated_at' => '2018-10-12 16:13:22',
            ),
            57 => 
            array (
                'id' => 67,
                'resource_category_id' => 33,
                'abbrv' => 'w/Defib & hands free',
                'description' => 'with defibrillator and hands free',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:18:11',
                'updated_at' => '2018-10-12 16:18:11',
            ),
            58 => 
            array (
                'id' => 68,
                'resource_category_id' => 33,
                'abbrv' => 'MH Vials, syringes, airwy',
                'description' => 'MH Vials, syringes, airway',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:19:02',
                'updated_at' => '2018-10-12 16:19:02',
            ),
            59 => 
            array (
                'id' => 69,
                'resource_category_id' => 34,
                'abbrv' => '3G',
                'description' => 'Laerdal 3G',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:25:17',
                'updated_at' => '2018-10-12 16:25:17',
            ),
            60 => 
            array (
                'id' => 70,
                'resource_category_id' => 34,
                'abbrv' => 'ALS',
                'description' => 'ALS Manikin',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:25:43',
                'updated_at' => '2018-10-12 16:25:43',
            ),
            61 => 
            array (
                'id' => 71,
                'resource_category_id' => 34,
                'abbrv' => 'Essential',
                'description' => 'Laerdal Essential SimMan',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:26:20',
                'updated_at' => '2018-10-12 16:26:20',
            ),
            62 => 
            array (
                'id' => 72,
                'resource_category_id' => 34,
                'abbrv' => 'Pelvic',
                'description' => 'Pelvic Exam Simulator',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:26:51',
                'updated_at' => '2018-10-12 16:26:51',
            ),
            63 => 
            array (
                'id' => 73,
                'resource_category_id' => 34,
                'abbrv' => 'SimBaby',
                'description' => 'Laerdal SimBaby',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:27:12',
                'updated_at' => '2018-10-12 16:27:12',
            ),
            64 => 
            array (
                'id' => 74,
                'resource_category_id' => 34,
                'abbrv' => 'SimMan',
                'description' => 'Laerdal SimMan',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:27:29',
                'updated_at' => '2018-10-12 16:27:29',
            ),
            65 => 
            array (
                'id' => 75,
                'resource_category_id' => 34,
                'abbrv' => 'SimNewB',
                'description' => 'Laerdal SimNewB',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:27:56',
                'updated_at' => '2018-10-12 16:27:56',
            ),
            66 => 
            array (
                'id' => 76,
                'resource_category_id' => 35,
                'abbrv' => 'FLS Box',
                'description' => 'FLS Box Practice Only',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:33:03',
                'updated_at' => '2018-10-12 16:33:03',
            ),
            67 => 
            array (
                'id' => 77,
                'resource_category_id' => 35,
                'abbrv' => 'Lap VR - CAE',
            'description' => 'Lap VR - CAE (Used for Physician Training)',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:33:41',
                'updated_at' => '2018-10-12 16:33:41',
            ),
            68 => 
            array (
                'id' => 78,
                'resource_category_id' => 35,
                'abbrv' => 'LapSim Tour',
                'description' => 'Original Black Box used for tours only',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:34:19',
                'updated_at' => '2018-10-12 16:34:19',
            ),
            69 => 
            array (
                'id' => 79,
                'resource_category_id' => 36,
                'abbrv' => 'Clinical',
                'description' => 'Clinical Exam Room',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 16:43:46',
                'updated_at' => '2018-10-12 16:43:46',
            ),
            70 => 
            array (
                'id' => 80,
                'resource_category_id' => 37,
                'abbrv' => 'Large',
                'description' => '150-200',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:04:43',
                'updated_at' => '2018-10-12 17:04:43',
            ),
            71 => 
            array (
                'id' => 81,
                'resource_category_id' => 37,
                'abbrv' => 'Medium',
                'description' => '50-150',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:04:56',
                'updated_at' => '2018-10-12 17:04:56',
            ),
            72 => 
            array (
                'id' => 82,
                'resource_category_id' => 37,
                'abbrv' => 'Small',
                'description' => 'Less then 50',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:05:08',
                'updated_at' => '2018-10-12 17:05:08',
            ),
            73 => 
            array (
                'id' => 83,
                'resource_category_id' => 38,
                'abbrv' => 'CVC',
                'description' => 'CVC',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:13:34',
                'updated_at' => '2018-10-12 17:13:34',
            ),
            74 => 
            array (
                'id' => 84,
                'resource_category_id' => 38,
                'abbrv' => 'LP Baby',
                'description' => 'LP Baby',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:13:47',
                'updated_at' => '2018-10-12 17:13:47',
            ),
            75 => 
            array (
                'id' => 85,
                'resource_category_id' => 39,
                'abbrv' => 'Senior',
                'description' => 'Senior Tech',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:17:37',
                'updated_at' => '2018-10-12 17:17:37',
            ),
            76 => 
            array (
                'id' => 86,
                'resource_category_id' => 39,
                'abbrv' => 'Junior',
                'description' => 'Junior Tech',
                'retire_date' => NULL,
                'created_at' => '2018-10-12 17:17:55',
                'updated_at' => '2018-10-12 17:17:55',
            ),
        ));
        
        
    }
}