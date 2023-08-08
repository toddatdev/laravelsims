<?php

use Illuminate\Database\Seeder;

class TimezonesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('timezones')->delete();
        
        DB::table('timezones')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tz' => 'Africa/Abidjan',
                'visible' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'tz' => 'Africa/Accra',
                'visible' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'tz' => 'Africa/Asmara',
                'visible' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'tz' => 'Africa/Bamako',
                'visible' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'tz' => 'Africa/Banjul',
                'visible' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'tz' => 'Africa/Bissau',
                'visible' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'tz' => 'Africa/Blantyre',
                'visible' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'tz' => 'Africa/Brazzaville',
                'visible' => 0,
            ),
            8 => 
            array (
                'id' => 9,
                'tz' => 'Africa/Bujumbura',
                'visible' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'tz' => 'Africa/Cairo',
                'visible' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'tz' => 'Africa/Casablanca',
                'visible' => 0,
            ),
            11 => 
            array (
                'id' => 12,
                'tz' => 'Africa/Ceuta',
                'visible' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'tz' => 'Africa/Conakry',
                'visible' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'tz' => 'Africa/Dakar',
                'visible' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'tz' => 'Africa/Dar_es_Salaam',
                'visible' => 0,
            ),
            15 => 
            array (
                'id' => 16,
                'tz' => 'Africa/Djibouti',
                'visible' => 0,
            ),
            16 => 
            array (
                'id' => 17,
                'tz' => 'Africa/Douala',
                'visible' => 0,
            ),
            17 => 
            array (
                'id' => 18,
                'tz' => 'Africa/El_Aaiun',
                'visible' => 0,
            ),
            18 => 
            array (
                'id' => 19,
                'tz' => 'Africa/Freetown',
                'visible' => 0,
            ),
            19 => 
            array (
                'id' => 20,
                'tz' => 'Africa/Gaborone',
                'visible' => 0,
            ),
            20 => 
            array (
                'id' => 21,
                'tz' => 'Africa/Harare',
                'visible' => 0,
            ),
            21 => 
            array (
                'id' => 22,
                'tz' => 'Africa/Johannesburg',
                'visible' => 0,
            ),
            22 => 
            array (
                'id' => 23,
                'tz' => 'Africa/Juba',
                'visible' => 0,
            ),
            23 => 
            array (
                'id' => 24,
                'tz' => 'Africa/Kampala',
                'visible' => 0,
            ),
            24 => 
            array (
                'id' => 25,
                'tz' => 'Africa/Khartoum',
                'visible' => 0,
            ),
            25 => 
            array (
                'id' => 26,
                'tz' => 'Africa/Kigali',
                'visible' => 0,
            ),
            26 => 
            array (
                'id' => 27,
                'tz' => 'Africa/Kinshasa',
                'visible' => 0,
            ),
            27 => 
            array (
                'id' => 28,
                'tz' => 'Africa/Lagos',
                'visible' => 0,
            ),
            28 => 
            array (
                'id' => 29,
                'tz' => 'Africa/Lagos|Africa/Bangui',
                'visible' => 0,
            ),
            29 => 
            array (
                'id' => 30,
                'tz' => 'Africa/Libreville',
                'visible' => 0,
            ),
            30 => 
            array (
                'id' => 31,
                'tz' => 'Africa/Lome',
                'visible' => 0,
            ),
            31 => 
            array (
                'id' => 32,
                'tz' => 'Africa/Luanda',
                'visible' => 0,
            ),
            32 => 
            array (
                'id' => 33,
                'tz' => 'Africa/Lubumbashi',
                'visible' => 0,
            ),
            33 => 
            array (
                'id' => 34,
                'tz' => 'Africa/Lusaka',
                'visible' => 0,
            ),
            34 => 
            array (
                'id' => 35,
                'tz' => 'Africa/Malabo',
                'visible' => 0,
            ),
            35 => 
            array (
                'id' => 36,
                'tz' => 'Africa/Maputo',
                'visible' => 0,
            ),
            36 => 
            array (
                'id' => 37,
                'tz' => 'Africa/Maseru',
                'visible' => 0,
            ),
            37 => 
            array (
                'id' => 38,
                'tz' => 'Africa/Mbabane',
                'visible' => 0,
            ),
            38 => 
            array (
                'id' => 39,
                'tz' => 'Africa/Mogadishu',
                'visible' => 0,
            ),
            39 => 
            array (
                'id' => 40,
                'tz' => 'Africa/Monrovia',
                'visible' => 0,
            ),
            40 => 
            array (
                'id' => 41,
                'tz' => 'Africa/Nairobi',
                'visible' => 0,
            ),
            41 => 
            array (
                'id' => 42,
                'tz' => 'Africa/Ndjamena',
                'visible' => 0,
            ),
            42 => 
            array (
                'id' => 43,
                'tz' => 'Africa/Niamey',
                'visible' => 0,
            ),
            43 => 
            array (
                'id' => 44,
                'tz' => 'Africa/Nouakchott',
                'visible' => 0,
            ),
            44 => 
            array (
                'id' => 45,
                'tz' => 'Africa/Ouagadougou',
                'visible' => 0,
            ),
            45 => 
            array (
                'id' => 46,
                'tz' => 'Africa/Porto-Novo',
                'visible' => 0,
            ),
            46 => 
            array (
                'id' => 47,
                'tz' => 'Africa/Sao_Tome',
                'visible' => 0,
            ),
            47 => 
            array (
                'id' => 48,
                'tz' => 'Africa/Timbuktu',
                'visible' => 0,
            ),
            48 => 
            array (
                'id' => 49,
                'tz' => 'Africa/Tripoli',
                'visible' => 0,
            ),
            49 => 
            array (
                'id' => 50,
                'tz' => 'Africa/Tunis',
                'visible' => 0,
            ),
            50 => 
            array (
                'id' => 51,
                'tz' => 'Africa/Windhoek',
                'visible' => 0,
            ),
            51 => 
            array (
                'id' => 52,
                'tz' => 'America/Adak',
                'visible' => 0,
            ),
            52 => 
            array (
                'id' => 53,
                'tz' => 'America/Anchorage',
                'visible' => 0,
            ),
            53 => 
            array (
                'id' => 54,
                'tz' => 'America/Anguilla',
                'visible' => 0,
            ),
            54 => 
            array (
                'id' => 55,
                'tz' => 'America/Antigua',
                'visible' => 0,
            ),
            55 => 
            array (
                'id' => 56,
                'tz' => 'America/Araguaina',
                'visible' => 0,
            ),
            56 => 
            array (
                'id' => 57,
                'tz' => 'America/Argentina/Buenos_Aires',
                'visible' => 0,
            ),
            57 => 
            array (
                'id' => 58,
                'tz' => 'America/Argentina/Catamarca',
                'visible' => 0,
            ),
            58 => 
            array (
                'id' => 59,
                'tz' => 'America/Argentina/ComodRivadavia',
                'visible' => 0,
            ),
            59 => 
            array (
                'id' => 60,
                'tz' => 'America/Argentina/Cordoba',
                'visible' => 0,
            ),
            60 => 
            array (
                'id' => 61,
                'tz' => 'America/Argentina/Jujuy',
                'visible' => 0,
            ),
            61 => 
            array (
                'id' => 62,
                'tz' => 'America/Argentina/La_Rioja',
                'visible' => 0,
            ),
            62 => 
            array (
                'id' => 63,
                'tz' => 'America/Argentina/Mendoza',
                'visible' => 0,
            ),
            63 => 
            array (
                'id' => 64,
                'tz' => 'America/Argentina/Rio_Gallegos',
                'visible' => 0,
            ),
            64 => 
            array (
                'id' => 65,
                'tz' => 'America/Argentina/Salta',
                'visible' => 0,
            ),
            65 => 
            array (
                'id' => 66,
                'tz' => 'America/Argentina/San_Juan',
                'visible' => 0,
            ),
            66 => 
            array (
                'id' => 67,
                'tz' => 'America/Argentina/San_Luis',
                'visible' => 0,
            ),
            67 => 
            array (
                'id' => 68,
                'tz' => 'America/Argentina/Tucuman',
                'visible' => 0,
            ),
            68 => 
            array (
                'id' => 69,
                'tz' => 'America/Argentina/Ushuaia',
                'visible' => 0,
            ),
            69 => 
            array (
                'id' => 70,
                'tz' => 'America/Aruba',
                'visible' => 0,
            ),
            70 => 
            array (
                'id' => 71,
                'tz' => 'America/Asuncion',
                'visible' => 0,
            ),
            71 => 
            array (
                'id' => 72,
                'tz' => 'America/Atikokan',
                'visible' => 0,
            ),
            72 => 
            array (
                'id' => 73,
                'tz' => 'America/Atka',
                'visible' => 0,
            ),
            73 => 
            array (
                'id' => 74,
                'tz' => 'America/Bahia',
                'visible' => 0,
            ),
            74 => 
            array (
                'id' => 75,
                'tz' => 'America/Bahia_Banderas',
                'visible' => 0,
            ),
            75 => 
            array (
                'id' => 76,
                'tz' => 'America/Barbados',
                'visible' => 0,
            ),
            76 => 
            array (
                'id' => 77,
                'tz' => 'America/Belem',
                'visible' => 0,
            ),
            77 => 
            array (
                'id' => 78,
                'tz' => 'America/Belize',
                'visible' => 0,
            ),
            78 => 
            array (
                'id' => 79,
                'tz' => 'America/Blanc-Sablon',
                'visible' => 0,
            ),
            79 => 
            array (
                'id' => 80,
                'tz' => 'America/Boa_Vista',
                'visible' => 0,
            ),
            80 => 
            array (
                'id' => 81,
                'tz' => 'America/Bogota',
                'visible' => 0,
            ),
            81 => 
            array (
                'id' => 82,
                'tz' => 'America/Boise',
                'visible' => 0,
            ),
            82 => 
            array (
                'id' => 83,
                'tz' => 'America/Buenos_Aires',
                'visible' => 0,
            ),
            83 => 
            array (
                'id' => 84,
                'tz' => 'America/Cambridge_Bay',
                'visible' => 0,
            ),
            84 => 
            array (
                'id' => 85,
                'tz' => 'America/Campo_Grande',
                'visible' => 0,
            ),
            85 => 
            array (
                'id' => 86,
                'tz' => 'America/Cancun',
                'visible' => 0,
            ),
            86 => 
            array (
                'id' => 87,
                'tz' => 'America/Caracas',
                'visible' => 0,
            ),
            87 => 
            array (
                'id' => 88,
                'tz' => 'America/Catamarca',
                'visible' => 0,
            ),
            88 => 
            array (
                'id' => 89,
                'tz' => 'America/Cayenne',
                'visible' => 0,
            ),
            89 => 
            array (
                'id' => 90,
                'tz' => 'America/Cayman',
                'visible' => 0,
            ),
            90 => 
            array (
                'id' => 91,
                'tz' => 'America/Chicago',
                'visible' => 1,
            ),
            91 => 
            array (
                'id' => 92,
                'tz' => 'America/Chihuahua',
                'visible' => 0,
            ),
            92 => 
            array (
                'id' => 93,
                'tz' => 'America/Coral_Harbour',
                'visible' => 0,
            ),
            93 => 
            array (
                'id' => 94,
                'tz' => 'America/Cordoba',
                'visible' => 0,
            ),
            94 => 
            array (
                'id' => 95,
                'tz' => 'America/Costa_Rica',
                'visible' => 0,
            ),
            95 => 
            array (
                'id' => 96,
                'tz' => 'America/Creston',
                'visible' => 0,
            ),
            96 => 
            array (
                'id' => 97,
                'tz' => 'America/Cuiaba',
                'visible' => 0,
            ),
            97 => 
            array (
                'id' => 98,
                'tz' => 'America/Curacao',
                'visible' => 0,
            ),
            98 => 
            array (
                'id' => 99,
                'tz' => 'America/Danmarkshavn',
                'visible' => 0,
            ),
            99 => 
            array (
                'id' => 100,
                'tz' => 'America/Dawson',
                'visible' => 0,
            ),
            100 => 
            array (
                'id' => 101,
                'tz' => 'America/Dawson_Creek',
                'visible' => 0,
            ),
            101 => 
            array (
                'id' => 102,
                'tz' => 'America/Denver',
                'visible' => 1,
            ),
            102 => 
            array (
                'id' => 103,
                'tz' => 'America/Detroit',
                'visible' => 0,
            ),
            103 => 
            array (
                'id' => 104,
                'tz' => 'America/Dominica',
                'visible' => 0,
            ),
            104 => 
            array (
                'id' => 105,
                'tz' => 'America/Edmonton',
                'visible' => 1,
            ),
            105 => 
            array (
                'id' => 106,
                'tz' => 'America/Eirunepe',
                'visible' => 0,
            ),
            106 => 
            array (
                'id' => 107,
                'tz' => 'America/El_Salvador',
                'visible' => 0,
            ),
            107 => 
            array (
                'id' => 108,
                'tz' => 'America/Ensenada',
                'visible' => 0,
            ),
            108 => 
            array (
                'id' => 109,
                'tz' => 'America/Fort_Nelson',
                'visible' => 0,
            ),
            109 => 
            array (
                'id' => 110,
                'tz' => 'America/Fort_Wayne',
                'visible' => 0,
            ),
            110 => 
            array (
                'id' => 111,
                'tz' => 'America/Fortaleza',
                'visible' => 0,
            ),
            111 => 
            array (
                'id' => 112,
                'tz' => 'America/Glace_Bay',
                'visible' => 0,
            ),
            112 => 
            array (
                'id' => 113,
                'tz' => 'America/Godthab',
                'visible' => 0,
            ),
            113 => 
            array (
                'id' => 114,
                'tz' => 'America/Goose_Bay',
                'visible' => 0,
            ),
            114 => 
            array (
                'id' => 115,
                'tz' => 'America/Grand_Turk',
                'visible' => 0,
            ),
            115 => 
            array (
                'id' => 116,
                'tz' => 'America/Grenada',
                'visible' => 0,
            ),
            116 => 
            array (
                'id' => 117,
                'tz' => 'America/Guadeloupe',
                'visible' => 0,
            ),
            117 => 
            array (
                'id' => 118,
                'tz' => 'America/Guatemala',
                'visible' => 0,
            ),
            118 => 
            array (
                'id' => 119,
                'tz' => 'America/Guayaquil',
                'visible' => 0,
            ),
            119 => 
            array (
                'id' => 120,
                'tz' => 'America/Guyana',
                'visible' => 0,
            ),
            120 => 
            array (
                'id' => 121,
                'tz' => 'America/Halifax',
                'visible' => 0,
            ),
            121 => 
            array (
                'id' => 122,
                'tz' => 'America/Havana',
                'visible' => 0,
            ),
            122 => 
            array (
                'id' => 123,
                'tz' => 'America/Hermosillo',
                'visible' => 0,
            ),
            123 => 
            array (
                'id' => 124,
                'tz' => 'America/Indiana/Indianapolis',
                'visible' => 0,
            ),
            124 => 
            array (
                'id' => 125,
                'tz' => 'America/Indiana/Knox',
                'visible' => 0,
            ),
            125 => 
            array (
                'id' => 126,
                'tz' => 'America/Indiana/Marengo',
                'visible' => 0,
            ),
            126 => 
            array (
                'id' => 127,
                'tz' => 'America/Indiana/Petersburg',
                'visible' => 0,
            ),
            127 => 
            array (
                'id' => 128,
                'tz' => 'America/Indiana/Tell_City',
                'visible' => 0,
            ),
            128 => 
            array (
                'id' => 129,
                'tz' => 'America/Indiana/Vevay',
                'visible' => 0,
            ),
            129 => 
            array (
                'id' => 130,
                'tz' => 'America/Indiana/Vincennes',
                'visible' => 0,
            ),
            130 => 
            array (
                'id' => 131,
                'tz' => 'America/Indiana/Winamac',
                'visible' => 0,
            ),
            131 => 
            array (
                'id' => 132,
                'tz' => 'America/Indianapolis',
                'visible' => 0,
            ),
            132 => 
            array (
                'id' => 133,
                'tz' => 'America/Inuvik',
                'visible' => 0,
            ),
            133 => 
            array (
                'id' => 134,
                'tz' => 'America/Iqaluit',
                'visible' => 0,
            ),
            134 => 
            array (
                'id' => 135,
                'tz' => 'America/Jamaica',
                'visible' => 0,
            ),
            135 => 
            array (
                'id' => 136,
                'tz' => 'America/Jujuy',
                'visible' => 0,
            ),
            136 => 
            array (
                'id' => 137,
                'tz' => 'America/Juneau',
                'visible' => 0,
            ),
            137 => 
            array (
                'id' => 138,
                'tz' => 'America/Kentucky/Louisville',
                'visible' => 0,
            ),
            138 => 
            array (
                'id' => 139,
                'tz' => 'America/Kentucky/Monticello',
                'visible' => 0,
            ),
            139 => 
            array (
                'id' => 140,
                'tz' => 'America/Knox_IN',
                'visible' => 0,
            ),
            140 => 
            array (
                'id' => 141,
                'tz' => 'America/Kralendijk',
                'visible' => 0,
            ),
            141 => 
            array (
                'id' => 142,
                'tz' => 'America/La_Paz',
                'visible' => 0,
            ),
            142 => 
            array (
                'id' => 143,
                'tz' => 'America/Lima',
                'visible' => 0,
            ),
            143 => 
            array (
                'id' => 144,
                'tz' => 'America/Los_Angeles',
                'visible' => 1,
            ),
            144 => 
            array (
                'id' => 145,
                'tz' => 'America/Louisville',
                'visible' => 0,
            ),
            145 => 
            array (
                'id' => 146,
                'tz' => 'America/Lower_Princes',
                'visible' => 0,
            ),
            146 => 
            array (
                'id' => 147,
                'tz' => 'America/Maceio',
                'visible' => 0,
            ),
            147 => 
            array (
                'id' => 148,
                'tz' => 'America/Managua',
                'visible' => 0,
            ),
            148 => 
            array (
                'id' => 149,
                'tz' => 'America/Manaus',
                'visible' => 0,
            ),
            149 => 
            array (
                'id' => 150,
                'tz' => 'America/Marigot',
                'visible' => 0,
            ),
            150 => 
            array (
                'id' => 151,
                'tz' => 'America/Martinique',
                'visible' => 0,
            ),
            151 => 
            array (
                'id' => 152,
                'tz' => 'America/Matamoros',
                'visible' => 0,
            ),
            152 => 
            array (
                'id' => 153,
                'tz' => 'America/Mazatlan',
                'visible' => 0,
            ),
            153 => 
            array (
                'id' => 154,
                'tz' => 'America/Mendoza',
                'visible' => 0,
            ),
            154 => 
            array (
                'id' => 155,
                'tz' => 'America/Menominee',
                'visible' => 0,
            ),
            155 => 
            array (
                'id' => 156,
                'tz' => 'America/Merida',
                'visible' => 0,
            ),
            156 => 
            array (
                'id' => 157,
                'tz' => 'America/Metlakatla',
                'visible' => 0,
            ),
            157 => 
            array (
                'id' => 158,
                'tz' => 'America/Mexico_City',
                'visible' => 0,
            ),
            158 => 
            array (
                'id' => 159,
                'tz' => 'America/Miquelon',
                'visible' => 0,
            ),
            159 => 
            array (
                'id' => 160,
                'tz' => 'America/Moncton',
                'visible' => 0,
            ),
            160 => 
            array (
                'id' => 161,
                'tz' => 'America/Monterrey',
                'visible' => 0,
            ),
            161 => 
            array (
                'id' => 162,
                'tz' => 'America/Montevideo',
                'visible' => 0,
            ),
            162 => 
            array (
                'id' => 163,
                'tz' => 'America/Montreal',
                'visible' => 0,
            ),
            163 => 
            array (
                'id' => 164,
                'tz' => 'America/Montserrat',
                'visible' => 0,
            ),
            164 => 
            array (
                'id' => 165,
                'tz' => 'America/Nassau',
                'visible' => 0,
            ),
            165 => 
            array (
                'id' => 166,
                'tz' => 'America/New_York',
                'visible' => 1,
            ),
            166 => 
            array (
                'id' => 167,
                'tz' => 'America/Nipigon',
                'visible' => 0,
            ),
            167 => 
            array (
                'id' => 168,
                'tz' => 'America/Nome',
                'visible' => 0,
            ),
            168 => 
            array (
                'id' => 169,
                'tz' => 'America/Noronha',
                'visible' => 0,
            ),
            169 => 
            array (
                'id' => 170,
                'tz' => 'America/North_Dakota/Beulah',
                'visible' => 0,
            ),
            170 => 
            array (
                'id' => 171,
                'tz' => 'America/North_Dakota/Center',
                'visible' => 0,
            ),
            171 => 
            array (
                'id' => 172,
                'tz' => 'America/North_Dakota/New_Salem',
                'visible' => 0,
            ),
            172 => 
            array (
                'id' => 173,
                'tz' => 'America/Ojinaga',
                'visible' => 0,
            ),
            173 => 
            array (
                'id' => 174,
                'tz' => 'America/Panama',
                'visible' => 0,
            ),
            174 => 
            array (
                'id' => 175,
                'tz' => 'America/Pangnirtung',
                'visible' => 0,
            ),
            175 => 
            array (
                'id' => 176,
                'tz' => 'America/Paramaribo',
                'visible' => 0,
            ),
            176 => 
            array (
                'id' => 177,
                'tz' => 'America/Phoenix',
                'visible' => 0,
            ),
            177 => 
            array (
                'id' => 178,
                'tz' => 'America/Port_of_Spain',
                'visible' => 0,
            ),
            178 => 
            array (
                'id' => 179,
                'tz' => 'America/Port-au-Prince',
                'visible' => 0,
            ),
            179 => 
            array (
                'id' => 180,
                'tz' => 'America/Porto_Acre',
                'visible' => 0,
            ),
            180 => 
            array (
                'id' => 181,
                'tz' => 'America/Porto_Velho',
                'visible' => 0,
            ),
            181 => 
            array (
                'id' => 182,
                'tz' => 'America/Puerto_Rico',
                'visible' => 0,
            ),
            182 => 
            array (
                'id' => 183,
                'tz' => 'America/Punta_Arenas',
                'visible' => 0,
            ),
            183 => 
            array (
                'id' => 184,
                'tz' => 'America/Rainy_River',
                'visible' => 0,
            ),
            184 => 
            array (
                'id' => 185,
                'tz' => 'America/Rankin_Inlet',
                'visible' => 0,
            ),
            185 => 
            array (
                'id' => 186,
                'tz' => 'America/Recife',
                'visible' => 0,
            ),
            186 => 
            array (
                'id' => 187,
                'tz' => 'America/Regina',
                'visible' => 0,
            ),
            187 => 
            array (
                'id' => 188,
                'tz' => 'America/Resolute',
                'visible' => 0,
            ),
            188 => 
            array (
                'id' => 189,
                'tz' => 'America/Rio_Branco',
                'visible' => 0,
            ),
            189 => 
            array (
                'id' => 190,
                'tz' => 'America/Rosario',
                'visible' => 0,
            ),
            190 => 
            array (
                'id' => 191,
                'tz' => 'America/Santa_Isabel',
                'visible' => 0,
            ),
            191 => 
            array (
                'id' => 192,
                'tz' => 'America/Santarem',
                'visible' => 0,
            ),
            192 => 
            array (
                'id' => 193,
                'tz' => 'America/Santiago',
                'visible' => 0,
            ),
            193 => 
            array (
                'id' => 194,
                'tz' => 'America/Santo_Domingo',
                'visible' => 0,
            ),
            194 => 
            array (
                'id' => 195,
                'tz' => 'America/Sao_Paulo',
                'visible' => 0,
            ),
            195 => 
            array (
                'id' => 196,
                'tz' => 'America/Scoresbysund',
                'visible' => 0,
            ),
            196 => 
            array (
                'id' => 197,
                'tz' => 'America/Shiprock',
                'visible' => 0,
            ),
            197 => 
            array (
                'id' => 198,
                'tz' => 'America/Sitka',
                'visible' => 0,
            ),
            198 => 
            array (
                'id' => 199,
                'tz' => 'America/St_Barthelemy',
                'visible' => 0,
            ),
            199 => 
            array (
                'id' => 200,
                'tz' => 'America/St_Johns',
                'visible' => 0,
            ),
            200 => 
            array (
                'id' => 201,
                'tz' => 'America/St_Kitts',
                'visible' => 0,
            ),
            201 => 
            array (
                'id' => 202,
                'tz' => 'America/St_Lucia',
                'visible' => 0,
            ),
            202 => 
            array (
                'id' => 203,
                'tz' => 'America/St_Thomas',
                'visible' => 0,
            ),
            203 => 
            array (
                'id' => 204,
                'tz' => 'America/St_Vincent',
                'visible' => 0,
            ),
            204 => 
            array (
                'id' => 205,
                'tz' => 'America/Swift_Current',
                'visible' => 0,
            ),
            205 => 
            array (
                'id' => 206,
                'tz' => 'America/Tegucigalpa',
                'visible' => 0,
            ),
            206 => 
            array (
                'id' => 207,
                'tz' => 'America/Thule',
                'visible' => 0,
            ),
            207 => 
            array (
                'id' => 208,
                'tz' => 'America/Thunder_Bay',
                'visible' => 0,
            ),
            208 => 
            array (
                'id' => 209,
                'tz' => 'America/Tijuana',
                'visible' => 0,
            ),
            209 => 
            array (
                'id' => 210,
                'tz' => 'America/Toronto',
                'visible' => 0,
            ),
            210 => 
            array (
                'id' => 211,
                'tz' => 'America/Tortola',
                'visible' => 0,
            ),
            211 => 
            array (
                'id' => 212,
                'tz' => 'America/Vancouver',
                'visible' => 0,
            ),
            212 => 
            array (
                'id' => 213,
                'tz' => 'America/Virgin',
                'visible' => 0,
            ),
            213 => 
            array (
                'id' => 214,
                'tz' => 'America/Whitehorse',
                'visible' => 0,
            ),
            214 => 
            array (
                'id' => 215,
                'tz' => 'America/Winnipeg',
                'visible' => 0,
            ),
            215 => 
            array (
                'id' => 216,
                'tz' => 'America/Yakutat',
                'visible' => 0,
            ),
            216 => 
            array (
                'id' => 217,
                'tz' => 'America/Yellowknife',
                'visible' => 0,
            ),
            217 => 
            array (
                'id' => 218,
                'tz' => 'Antarctica/Casey',
                'visible' => 0,
            ),
            218 => 
            array (
                'id' => 219,
                'tz' => 'Antarctica/Davis',
                'visible' => 0,
            ),
            219 => 
            array (
                'id' => 220,
                'tz' => 'Antarctica/DumontDUrville',
                'visible' => 0,
            ),
            220 => 
            array (
                'id' => 221,
                'tz' => 'Antarctica/Macquarie',
                'visible' => 0,
            ),
            221 => 
            array (
                'id' => 222,
                'tz' => 'Antarctica/Mawson',
                'visible' => 0,
            ),
            222 => 
            array (
                'id' => 223,
                'tz' => 'Antarctica/McMurdo',
                'visible' => 0,
            ),
            223 => 
            array (
                'id' => 224,
                'tz' => 'Antarctica/Palmer',
                'visible' => 0,
            ),
            224 => 
            array (
                'id' => 225,
                'tz' => 'Antarctica/Rothera',
                'visible' => 0,
            ),
            225 => 
            array (
                'id' => 226,
                'tz' => 'Antarctica/South_Pole',
                'visible' => 0,
            ),
            226 => 
            array (
                'id' => 227,
                'tz' => 'Antarctica/Syowa',
                'visible' => 0,
            ),
            227 => 
            array (
                'id' => 228,
                'tz' => 'Antarctica/Troll',
                'visible' => 0,
            ),
            228 => 
            array (
                'id' => 229,
                'tz' => 'Antarctica/Vostok',
                'visible' => 0,
            ),
            229 => 
            array (
                'id' => 230,
                'tz' => 'Arctic/Longyearbyen',
                'visible' => 0,
            ),
            230 => 
            array (
                'id' => 231,
                'tz' => 'Asia/Aden',
                'visible' => 0,
            ),
            231 => 
            array (
                'id' => 232,
                'tz' => 'Asia/Almaty',
                'visible' => 0,
            ),
            232 => 
            array (
                'id' => 233,
                'tz' => 'Asia/Amman',
                'visible' => 0,
            ),
            233 => 
            array (
                'id' => 234,
                'tz' => 'Asia/Anadyr',
                'visible' => 0,
            ),
            234 => 
            array (
                'id' => 235,
                'tz' => 'Asia/Aqtau',
                'visible' => 0,
            ),
            235 => 
            array (
                'id' => 236,
                'tz' => 'Asia/Aqtobe',
                'visible' => 0,
            ),
            236 => 
            array (
                'id' => 237,
                'tz' => 'Asia/Ashgabat',
                'visible' => 0,
            ),
            237 => 
            array (
                'id' => 238,
                'tz' => 'Asia/Ashkhabad',
                'visible' => 0,
            ),
            238 => 
            array (
                'id' => 239,
                'tz' => 'Asia/Atyrau',
                'visible' => 0,
            ),
            239 => 
            array (
                'id' => 240,
                'tz' => 'Asia/Baghdad',
                'visible' => 0,
            ),
            240 => 
            array (
                'id' => 241,
                'tz' => 'Asia/Bahrain',
                'visible' => 0,
            ),
            241 => 
            array (
                'id' => 242,
                'tz' => 'Asia/Baku',
                'visible' => 0,
            ),
            242 => 
            array (
                'id' => 243,
                'tz' => 'Asia/Bangkok',
                'visible' => 0,
            ),
            243 => 
            array (
                'id' => 244,
                'tz' => 'Asia/Barnaul',
                'visible' => 0,
            ),
            244 => 
            array (
                'id' => 245,
                'tz' => 'Asia/Beirut',
                'visible' => 0,
            ),
            245 => 
            array (
                'id' => 246,
                'tz' => 'Asia/Bishkek',
                'visible' => 0,
            ),
            246 => 
            array (
                'id' => 247,
                'tz' => 'Asia/Brunei',
                'visible' => 0,
            ),
            247 => 
            array (
                'id' => 248,
                'tz' => 'Asia/Calcutta',
                'visible' => 0,
            ),
            248 => 
            array (
                'id' => 249,
                'tz' => 'Asia/Chita',
                'visible' => 0,
            ),
            249 => 
            array (
                'id' => 250,
                'tz' => 'Asia/Choibalsan',
                'visible' => 0,
            ),
            250 => 
            array (
                'id' => 251,
                'tz' => 'Asia/Chongqing',
                'visible' => 0,
            ),
            251 => 
            array (
                'id' => 252,
                'tz' => 'Asia/Chungking',
                'visible' => 0,
            ),
            252 => 
            array (
                'id' => 253,
                'tz' => 'Asia/Colombo',
                'visible' => 0,
            ),
            253 => 
            array (
                'id' => 254,
                'tz' => 'Asia/Dacca',
                'visible' => 0,
            ),
            254 => 
            array (
                'id' => 255,
                'tz' => 'Asia/Damascus',
                'visible' => 0,
            ),
            255 => 
            array (
                'id' => 256,
                'tz' => 'Asia/Dhaka',
                'visible' => 0,
            ),
            256 => 
            array (
                'id' => 257,
                'tz' => 'Asia/Dili',
                'visible' => 0,
            ),
            257 => 
            array (
                'id' => 258,
                'tz' => 'Asia/Dubai',
                'visible' => 0,
            ),
            258 => 
            array (
                'id' => 259,
                'tz' => 'Asia/Dushanbe',
                'visible' => 0,
            ),
            259 => 
            array (
                'id' => 260,
                'tz' => 'Asia/Famagusta',
                'visible' => 0,
            ),
            260 => 
            array (
                'id' => 261,
                'tz' => 'Asia/Gaza',
                'visible' => 0,
            ),
            261 => 
            array (
                'id' => 262,
                'tz' => 'Asia/Harbin',
                'visible' => 0,
            ),
            262 => 
            array (
                'id' => 263,
                'tz' => 'Asia/Hebron',
                'visible' => 0,
            ),
            263 => 
            array (
                'id' => 264,
                'tz' => 'Asia/Ho_Chi_Minh',
                'visible' => 0,
            ),
            264 => 
            array (
                'id' => 265,
                'tz' => 'Asia/Hong_Kong',
                'visible' => 0,
            ),
            265 => 
            array (
                'id' => 266,
                'tz' => 'Asia/Hovd',
                'visible' => 0,
            ),
            266 => 
            array (
                'id' => 267,
                'tz' => 'Asia/Irkutsk',
                'visible' => 0,
            ),
            267 => 
            array (
                'id' => 268,
                'tz' => 'Asia/Istanbul',
                'visible' => 0,
            ),
            268 => 
            array (
                'id' => 269,
                'tz' => 'Asia/Jakarta',
                'visible' => 0,
            ),
            269 => 
            array (
                'id' => 270,
                'tz' => 'Asia/Jayapura',
                'visible' => 0,
            ),
            270 => 
            array (
                'id' => 271,
                'tz' => 'Asia/Jerusalem',
                'visible' => 0,
            ),
            271 => 
            array (
                'id' => 272,
                'tz' => 'Asia/Kabul',
                'visible' => 0,
            ),
            272 => 
            array (
                'id' => 273,
                'tz' => 'Asia/Kamchatka',
                'visible' => 0,
            ),
            273 => 
            array (
                'id' => 274,
                'tz' => 'Asia/Karachi',
                'visible' => 0,
            ),
            274 => 
            array (
                'id' => 275,
                'tz' => 'Asia/Kashgar',
                'visible' => 0,
            ),
            275 => 
            array (
                'id' => 276,
                'tz' => 'Asia/Kathmandu',
                'visible' => 0,
            ),
            276 => 
            array (
                'id' => 277,
                'tz' => 'Asia/Katmandu',
                'visible' => 0,
            ),
            277 => 
            array (
                'id' => 278,
                'tz' => 'Asia/Khandyga',
                'visible' => 0,
            ),
            278 => 
            array (
                'id' => 279,
                'tz' => 'Asia/Kolkata',
                'visible' => 0,
            ),
            279 => 
            array (
                'id' => 280,
                'tz' => 'Asia/Krasnoyarsk',
                'visible' => 0,
            ),
            280 => 
            array (
                'id' => 281,
                'tz' => 'Asia/Kuala_Lumpur',
                'visible' => 0,
            ),
            281 => 
            array (
                'id' => 282,
                'tz' => 'Asia/Kuching',
                'visible' => 0,
            ),
            282 => 
            array (
                'id' => 283,
                'tz' => 'Asia/Kuwait',
                'visible' => 0,
            ),
            283 => 
            array (
                'id' => 284,
                'tz' => 'Asia/Macao',
                'visible' => 0,
            ),
            284 => 
            array (
                'id' => 285,
                'tz' => 'Asia/Macau',
                'visible' => 0,
            ),
            285 => 
            array (
                'id' => 286,
                'tz' => 'Asia/Magadan',
                'visible' => 0,
            ),
            286 => 
            array (
                'id' => 287,
                'tz' => 'Asia/Makassar',
                'visible' => 0,
            ),
            287 => 
            array (
                'id' => 288,
                'tz' => 'Asia/Manila',
                'visible' => 0,
            ),
            288 => 
            array (
                'id' => 289,
                'tz' => 'Asia/Muscat',
                'visible' => 0,
            ),
            289 => 
            array (
                'id' => 290,
                'tz' => 'Asia/Nicosia',
                'visible' => 0,
            ),
            290 => 
            array (
                'id' => 291,
                'tz' => 'Asia/Novokuznetsk',
                'visible' => 0,
            ),
            291 => 
            array (
                'id' => 292,
                'tz' => 'Asia/Novosibirsk',
                'visible' => 0,
            ),
            292 => 
            array (
                'id' => 293,
                'tz' => 'Asia/Omsk',
                'visible' => 0,
            ),
            293 => 
            array (
                'id' => 294,
                'tz' => 'Asia/Oral',
                'visible' => 0,
            ),
            294 => 
            array (
                'id' => 295,
                'tz' => 'Asia/Phnom_Penh',
                'visible' => 0,
            ),
            295 => 
            array (
                'id' => 296,
                'tz' => 'Asia/Pontianak',
                'visible' => 0,
            ),
            296 => 
            array (
                'id' => 297,
                'tz' => 'Asia/Pyongyang',
                'visible' => 0,
            ),
            297 => 
            array (
                'id' => 298,
                'tz' => 'Asia/Qatar',
                'visible' => 0,
            ),
            298 => 
            array (
                'id' => 299,
                'tz' => 'Asia/Qyzylorda',
                'visible' => 0,
            ),
            299 => 
            array (
                'id' => 300,
                'tz' => 'Asia/Rangoon',
                'visible' => 0,
            ),
            300 => 
            array (
                'id' => 301,
                'tz' => 'Asia/Riyadh',
                'visible' => 0,
            ),
            301 => 
            array (
                'id' => 302,
                'tz' => 'Asia/Saigon',
                'visible' => 0,
            ),
            302 => 
            array (
                'id' => 303,
                'tz' => 'Asia/Sakhalin',
                'visible' => 0,
            ),
            303 => 
            array (
                'id' => 304,
                'tz' => 'Asia/Samarkand',
                'visible' => 0,
            ),
            304 => 
            array (
                'id' => 305,
                'tz' => 'Asia/Seoul',
                'visible' => 0,
            ),
            305 => 
            array (
                'id' => 306,
                'tz' => 'Asia/Shanghai',
                'visible' => 1,
            ),
            306 => 
            array (
                'id' => 307,
                'tz' => 'Asia/Singapore',
                'visible' => 1,
            ),
            307 => 
            array (
                'id' => 308,
                'tz' => 'Asia/Srednekolymsk',
                'visible' => 0,
            ),
            308 => 
            array (
                'id' => 309,
                'tz' => 'Asia/Taipei',
                'visible' => 0,
            ),
            309 => 
            array (
                'id' => 310,
                'tz' => 'Asia/Tashkent',
                'visible' => 0,
            ),
            310 => 
            array (
                'id' => 311,
                'tz' => 'Asia/Tbilisi',
                'visible' => 0,
            ),
            311 => 
            array (
                'id' => 312,
                'tz' => 'Asia/Tehran',
                'visible' => 0,
            ),
            312 => 
            array (
                'id' => 313,
                'tz' => 'Asia/Tel_Aviv',
                'visible' => 0,
            ),
            313 => 
            array (
                'id' => 314,
                'tz' => 'Asia/Thimbu',
                'visible' => 0,
            ),
            314 => 
            array (
                'id' => 315,
                'tz' => 'Asia/Thimphu',
                'visible' => 0,
            ),
            315 => 
            array (
                'id' => 316,
                'tz' => 'Asia/Tokyo',
                'visible' => 0,
            ),
            316 => 
            array (
                'id' => 317,
                'tz' => 'Asia/Tomsk',
                'visible' => 0,
            ),
            317 => 
            array (
                'id' => 318,
                'tz' => 'Asia/Ujung_Pandang',
                'visible' => 0,
            ),
            318 => 
            array (
                'id' => 319,
                'tz' => 'Asia/Ulaanbaatar',
                'visible' => 0,
            ),
            319 => 
            array (
                'id' => 320,
                'tz' => 'Asia/Ulan_Bator',
                'visible' => 0,
            ),
            320 => 
            array (
                'id' => 321,
                'tz' => 'Asia/Urumqi',
                'visible' => 0,
            ),
            321 => 
            array (
                'id' => 322,
                'tz' => 'Asia/Ust-Nera',
                'visible' => 0,
            ),
            322 => 
            array (
                'id' => 323,
                'tz' => 'Asia/Vientiane',
                'visible' => 0,
            ),
            323 => 
            array (
                'id' => 324,
                'tz' => 'Asia/Vladivostok',
                'visible' => 0,
            ),
            324 => 
            array (
                'id' => 325,
                'tz' => 'Asia/Yakutsk',
                'visible' => 0,
            ),
            325 => 
            array (
                'id' => 326,
                'tz' => 'Asia/Yangon',
                'visible' => 0,
            ),
            326 => 
            array (
                'id' => 327,
                'tz' => 'Asia/Yekaterinburg',
                'visible' => 0,
            ),
            327 => 
            array (
                'id' => 328,
                'tz' => 'Asia/Yerevan',
                'visible' => 0,
            ),
            328 => 
            array (
                'id' => 329,
                'tz' => 'Atlantic/Azores',
                'visible' => 0,
            ),
            329 => 
            array (
                'id' => 330,
                'tz' => 'Atlantic/Bermuda',
                'visible' => 0,
            ),
            330 => 
            array (
                'id' => 331,
                'tz' => 'Atlantic/Canary',
                'visible' => 0,
            ),
            331 => 
            array (
                'id' => 332,
                'tz' => 'Atlantic/Cape_Verde',
                'visible' => 0,
            ),
            332 => 
            array (
                'id' => 333,
                'tz' => 'Atlantic/Faeroe',
                'visible' => 0,
            ),
            333 => 
            array (
                'id' => 334,
                'tz' => 'Atlantic/Faroe',
                'visible' => 0,
            ),
            334 => 
            array (
                'id' => 335,
                'tz' => 'Atlantic/Jan_Mayen',
                'visible' => 0,
            ),
            335 => 
            array (
                'id' => 336,
                'tz' => 'Atlantic/Madeira',
                'visible' => 0,
            ),
            336 => 
            array (
                'id' => 337,
                'tz' => 'Atlantic/Reykjavik',
                'visible' => 0,
            ),
            337 => 
            array (
                'id' => 338,
                'tz' => 'Atlantic/South_Georgia',
                'visible' => 0,
            ),
            338 => 
            array (
                'id' => 339,
                'tz' => 'Atlantic/St_Helena',
                'visible' => 0,
            ),
            339 => 
            array (
                'id' => 340,
                'tz' => 'Atlantic/Stanley',
                'visible' => 0,
            ),
            340 => 
            array (
                'id' => 341,
                'tz' => 'Australia/ACT',
                'visible' => 0,
            ),
            341 => 
            array (
                'id' => 342,
                'tz' => 'Australia/Adelaide',
                'visible' => 0,
            ),
            342 => 
            array (
                'id' => 343,
                'tz' => 'Australia/Brisbane',
                'visible' => 0,
            ),
            343 => 
            array (
                'id' => 344,
                'tz' => 'Australia/Broken_Hill',
                'visible' => 0,
            ),
            344 => 
            array (
                'id' => 345,
                'tz' => 'Australia/Canberra',
                'visible' => 0,
            ),
            345 => 
            array (
                'id' => 346,
                'tz' => 'Australia/Currie',
                'visible' => 0,
            ),
            346 => 
            array (
                'id' => 347,
                'tz' => 'Australia/Darwin',
                'visible' => 0,
            ),
            347 => 
            array (
                'id' => 348,
                'tz' => 'Australia/Eucla',
                'visible' => 0,
            ),
            348 => 
            array (
                'id' => 349,
                'tz' => 'Australia/Hobart',
                'visible' => 0,
            ),
            349 => 
            array (
                'id' => 350,
                'tz' => 'Australia/LHI',
                'visible' => 0,
            ),
            350 => 
            array (
                'id' => 351,
                'tz' => 'Australia/Lindeman',
                'visible' => 0,
            ),
            351 => 
            array (
                'id' => 352,
                'tz' => 'Australia/Lord_Howe',
                'visible' => 0,
            ),
            352 => 
            array (
                'id' => 353,
                'tz' => 'Australia/Melbourne',
                'visible' => 0,
            ),
            353 => 
            array (
                'id' => 354,
                'tz' => 'Australia/North',
                'visible' => 0,
            ),
            354 => 
            array (
                'id' => 355,
                'tz' => 'Australia/NSW',
                'visible' => 0,
            ),
            355 => 
            array (
                'id' => 356,
                'tz' => 'Australia/Perth',
                'visible' => 0,
            ),
            356 => 
            array (
                'id' => 357,
                'tz' => 'Australia/Queensland',
                'visible' => 0,
            ),
            357 => 
            array (
                'id' => 358,
                'tz' => 'Australia/South',
                'visible' => 0,
            ),
            358 => 
            array (
                'id' => 359,
                'tz' => 'Australia/Sydney',
                'visible' => 0,
            ),
            359 => 
            array (
                'id' => 360,
                'tz' => 'Australia/Tasmania',
                'visible' => 0,
            ),
            360 => 
            array (
                'id' => 361,
                'tz' => 'Australia/Victoria',
                'visible' => 0,
            ),
            361 => 
            array (
                'id' => 362,
                'tz' => 'Australia/West',
                'visible' => 0,
            ),
            362 => 
            array (
                'id' => 363,
                'tz' => 'Australia/Yancowinna',
                'visible' => 0,
            ),
            363 => 
            array (
                'id' => 364,
                'tz' => 'Brazil/Acre',
                'visible' => 0,
            ),
            364 => 
            array (
                'id' => 365,
                'tz' => 'Brazil/DeNoronha',
                'visible' => 0,
            ),
            365 => 
            array (
                'id' => 366,
                'tz' => 'Brazil/East',
                'visible' => 0,
            ),
            366 => 
            array (
                'id' => 367,
                'tz' => 'Brazil/West',
                'visible' => 0,
            ),
            367 => 
            array (
                'id' => 368,
                'tz' => 'Canada/Atlantic',
                'visible' => 0,
            ),
            368 => 
            array (
                'id' => 369,
                'tz' => 'Canada/Central',
                'visible' => 0,
            ),
            369 => 
            array (
                'id' => 370,
                'tz' => 'Canada/Eastern',
                'visible' => 0,
            ),
            370 => 
            array (
                'id' => 371,
                'tz' => 'Canada/Mountain',
                'visible' => 0,
            ),
            371 => 
            array (
                'id' => 372,
                'tz' => 'Canada/Newfoundland',
                'visible' => 0,
            ),
            372 => 
            array (
                'id' => 373,
                'tz' => 'Canada/Pacific',
                'visible' => 0,
            ),
            373 => 
            array (
                'id' => 374,
                'tz' => 'Canada/Saskatchewan',
                'visible' => 0,
            ),
            374 => 
            array (
                'id' => 375,
                'tz' => 'Canada/Yukon',
                'visible' => 0,
            ),
            375 => 
            array (
                'id' => 376,
                'tz' => 'Chile/Continental',
                'visible' => 0,
            ),
            376 => 
            array (
                'id' => 377,
                'tz' => 'Chile/EasterIsland',
                'visible' => 0,
            ),
            377 => 
            array (
                'id' => 378,
                'tz' => 'Europe/Amsterdam',
                'visible' => 0,
            ),
            378 => 
            array (
                'id' => 379,
                'tz' => 'Europe/Andorra',
                'visible' => 0,
            ),
            379 => 
            array (
                'id' => 380,
                'tz' => 'Europe/Astrakhan',
                'visible' => 0,
            ),
            380 => 
            array (
                'id' => 381,
                'tz' => 'Europe/Athens',
                'visible' => 0,
            ),
            381 => 
            array (
                'id' => 382,
                'tz' => 'Europe/Belfast',
                'visible' => 0,
            ),
            382 => 
            array (
                'id' => 383,
                'tz' => 'Europe/Belgrade',
                'visible' => 0,
            ),
            383 => 
            array (
                'id' => 384,
                'tz' => 'Europe/Berlin',
                'visible' => 0,
            ),
            384 => 
            array (
                'id' => 385,
                'tz' => 'Europe/Bratislava',
                'visible' => 0,
            ),
            385 => 
            array (
                'id' => 386,
                'tz' => 'Europe/Brussels',
                'visible' => 0,
            ),
            386 => 
            array (
                'id' => 387,
                'tz' => 'Europe/Bucharest',
                'visible' => 0,
            ),
            387 => 
            array (
                'id' => 388,
                'tz' => 'Europe/Budapest',
                'visible' => 0,
            ),
            388 => 
            array (
                'id' => 389,
                'tz' => 'Europe/Busingen',
                'visible' => 0,
            ),
            389 => 
            array (
                'id' => 390,
                'tz' => 'Europe/Chisinau',
                'visible' => 0,
            ),
            390 => 
            array (
                'id' => 391,
                'tz' => 'Europe/Copenhagen',
                'visible' => 0,
            ),
            391 => 
            array (
                'id' => 392,
                'tz' => 'Europe/Dublin',
                'visible' => 0,
            ),
            392 => 
            array (
                'id' => 393,
                'tz' => 'Europe/Gibraltar',
                'visible' => 0,
            ),
            393 => 
            array (
                'id' => 394,
                'tz' => 'Europe/Guernsey',
                'visible' => 0,
            ),
            394 => 
            array (
                'id' => 395,
                'tz' => 'Europe/Helsinki',
                'visible' => 0,
            ),
            395 => 
            array (
                'id' => 396,
                'tz' => 'Europe/Isle_of_Man',
                'visible' => 0,
            ),
            396 => 
            array (
                'id' => 397,
                'tz' => 'Europe/Istanbul',
                'visible' => 0,
            ),
            397 => 
            array (
                'id' => 398,
                'tz' => 'Europe/Jersey',
                'visible' => 0,
            ),
            398 => 
            array (
                'id' => 399,
                'tz' => 'Europe/Kaliningrad',
                'visible' => 0,
            ),
            399 => 
            array (
                'id' => 400,
                'tz' => 'Europe/Kiev',
                'visible' => 0,
            ),
            400 => 
            array (
                'id' => 401,
                'tz' => 'Europe/Kirov',
                'visible' => 0,
            ),
            401 => 
            array (
                'id' => 402,
                'tz' => 'Europe/Lisbon',
                'visible' => 0,
            ),
            402 => 
            array (
                'id' => 403,
                'tz' => 'Europe/Ljubljana',
                'visible' => 0,
            ),
            403 => 
            array (
                'id' => 404,
                'tz' => 'Europe/London',
                'visible' => 0,
            ),
            404 => 
            array (
                'id' => 405,
                'tz' => 'Europe/Luxembourg',
                'visible' => 0,
            ),
            405 => 
            array (
                'id' => 406,
                'tz' => 'Europe/Madrid',
                'visible' => 0,
            ),
            406 => 
            array (
                'id' => 407,
                'tz' => 'Europe/Malta',
                'visible' => 0,
            ),
            407 => 
            array (
                'id' => 408,
                'tz' => 'Europe/Mariehamn',
                'visible' => 0,
            ),
            408 => 
            array (
                'id' => 409,
                'tz' => 'Europe/Minsk',
                'visible' => 0,
            ),
            409 => 
            array (
                'id' => 410,
                'tz' => 'Europe/Monaco',
                'visible' => 0,
            ),
            410 => 
            array (
                'id' => 411,
                'tz' => 'Europe/Moscow',
                'visible' => 0,
            ),
            411 => 
            array (
                'id' => 412,
                'tz' => 'Europe/Nicosia',
                'visible' => 0,
            ),
            412 => 
            array (
                'id' => 413,
                'tz' => 'Europe/Oslo',
                'visible' => 0,
            ),
            413 => 
            array (
                'id' => 414,
                'tz' => 'Europe/Paris',
                'visible' => 0,
            ),
            414 => 
            array (
                'id' => 415,
                'tz' => 'Europe/Podgorica',
                'visible' => 0,
            ),
            415 => 
            array (
                'id' => 416,
                'tz' => 'Europe/Prague',
                'visible' => 0,
            ),
            416 => 
            array (
                'id' => 417,
                'tz' => 'Europe/Riga',
                'visible' => 0,
            ),
            417 => 
            array (
                'id' => 418,
                'tz' => 'Europe/Rome',
                'visible' => 0,
            ),
            418 => 
            array (
                'id' => 419,
                'tz' => 'Europe/Samara',
                'visible' => 0,
            ),
            419 => 
            array (
                'id' => 420,
                'tz' => 'Europe/San_Marino',
                'visible' => 0,
            ),
            420 => 
            array (
                'id' => 421,
                'tz' => 'Europe/Sarajevo',
                'visible' => 0,
            ),
            421 => 
            array (
                'id' => 422,
                'tz' => 'Europe/Saratov',
                'visible' => 0,
            ),
            422 => 
            array (
                'id' => 423,
                'tz' => 'Europe/Simferopol',
                'visible' => 0,
            ),
            423 => 
            array (
                'id' => 424,
                'tz' => 'Europe/Skopje',
                'visible' => 0,
            ),
            424 => 
            array (
                'id' => 425,
                'tz' => 'Europe/Sofia',
                'visible' => 0,
            ),
            425 => 
            array (
                'id' => 426,
                'tz' => 'Europe/Stockholm',
                'visible' => 0,
            ),
            426 => 
            array (
                'id' => 427,
                'tz' => 'Europe/Tallinn',
                'visible' => 0,
            ),
            427 => 
            array (
                'id' => 428,
                'tz' => 'Europe/Tirane',
                'visible' => 0,
            ),
            428 => 
            array (
                'id' => 429,
                'tz' => 'Europe/Tiraspol',
                'visible' => 0,
            ),
            429 => 
            array (
                'id' => 430,
                'tz' => 'Europe/Ulyanovsk',
                'visible' => 0,
            ),
            430 => 
            array (
                'id' => 431,
                'tz' => 'Europe/Uzhgorod',
                'visible' => 0,
            ),
            431 => 
            array (
                'id' => 432,
                'tz' => 'Europe/Vaduz',
                'visible' => 0,
            ),
            432 => 
            array (
                'id' => 433,
                'tz' => 'Europe/Vatican',
                'visible' => 0,
            ),
            433 => 
            array (
                'id' => 434,
                'tz' => 'Europe/Vienna',
                'visible' => 0,
            ),
            434 => 
            array (
                'id' => 435,
                'tz' => 'Europe/Vilnius',
                'visible' => 0,
            ),
            435 => 
            array (
                'id' => 436,
                'tz' => 'Europe/Volgograd',
                'visible' => 0,
            ),
            436 => 
            array (
                'id' => 437,
                'tz' => 'Europe/Warsaw',
                'visible' => 0,
            ),
            437 => 
            array (
                'id' => 438,
                'tz' => 'Europe/Zagreb',
                'visible' => 0,
            ),
            438 => 
            array (
                'id' => 439,
                'tz' => 'Europe/Zaporozhye',
                'visible' => 0,
            ),
            439 => 
            array (
                'id' => 440,
                'tz' => 'Europe/Zurich',
                'visible' => 0,
            ),
            440 => 
            array (
                'id' => 441,
                'tz' => 'Indian/Antananarivo',
                'visible' => 0,
            ),
            441 => 
            array (
                'id' => 442,
                'tz' => 'Indian/Chagos',
                'visible' => 0,
            ),
            442 => 
            array (
                'id' => 443,
                'tz' => 'Indian/Christmas',
                'visible' => 0,
            ),
            443 => 
            array (
                'id' => 444,
                'tz' => 'Indian/Cocos',
                'visible' => 0,
            ),
            444 => 
            array (
                'id' => 445,
                'tz' => 'Indian/Comoro',
                'visible' => 0,
            ),
            445 => 
            array (
                'id' => 446,
                'tz' => 'Indian/Kerguelen',
                'visible' => 0,
            ),
            446 => 
            array (
                'id' => 447,
                'tz' => 'Indian/Mahe',
                'visible' => 0,
            ),
            447 => 
            array (
                'id' => 448,
                'tz' => 'Indian/Maldives',
                'visible' => 0,
            ),
            448 => 
            array (
                'id' => 449,
                'tz' => 'Indian/Mauritius',
                'visible' => 0,
            ),
            449 => 
            array (
                'id' => 450,
                'tz' => 'Indian/Mayotte',
                'visible' => 0,
            ),
            450 => 
            array (
                'id' => 451,
                'tz' => 'Indian/Reunion',
                'visible' => 0,
            ),
            451 => 
            array (
                'id' => 452,
                'tz' => 'Mexico/BajaNorte',
                'visible' => 0,
            ),
            452 => 
            array (
                'id' => 453,
                'tz' => 'Mexico/BajaSur',
                'visible' => 0,
            ),
            453 => 
            array (
                'id' => 454,
                'tz' => 'Mexico/General',
                'visible' => 0,
            ),
            454 => 
            array (
                'id' => 455,
                'tz' => 'Pacific/Apia',
                'visible' => 0,
            ),
            455 => 
            array (
                'id' => 456,
                'tz' => 'Pacific/Auckland',
                'visible' => 0,
            ),
            456 => 
            array (
                'id' => 457,
                'tz' => 'Pacific/Bougainville',
                'visible' => 0,
            ),
            457 => 
            array (
                'id' => 458,
                'tz' => 'Pacific/Chatham',
                'visible' => 0,
            ),
            458 => 
            array (
                'id' => 459,
                'tz' => 'Pacific/Chuuk',
                'visible' => 0,
            ),
            459 => 
            array (
                'id' => 460,
                'tz' => 'Pacific/Easter',
                'visible' => 0,
            ),
            460 => 
            array (
                'id' => 461,
                'tz' => 'Pacific/Efate',
                'visible' => 0,
            ),
            461 => 
            array (
                'id' => 462,
                'tz' => 'Pacific/Enderbury',
                'visible' => 0,
            ),
            462 => 
            array (
                'id' => 463,
                'tz' => 'Pacific/Fakaofo',
                'visible' => 0,
            ),
            463 => 
            array (
                'id' => 464,
                'tz' => 'Pacific/Fiji',
                'visible' => 0,
            ),
            464 => 
            array (
                'id' => 465,
                'tz' => 'Pacific/Funafuti',
                'visible' => 0,
            ),
            465 => 
            array (
                'id' => 466,
                'tz' => 'Pacific/Galapagos',
                'visible' => 0,
            ),
            466 => 
            array (
                'id' => 467,
                'tz' => 'Pacific/Gambier',
                'visible' => 0,
            ),
            467 => 
            array (
                'id' => 468,
                'tz' => 'Pacific/Guadalcanal',
                'visible' => 0,
            ),
            468 => 
            array (
                'id' => 469,
                'tz' => 'Pacific/Guam',
                'visible' => 0,
            ),
            469 => 
            array (
                'id' => 470,
                'tz' => 'Pacific/Honolulu',
                'visible' => 1,
            ),
            470 => 
            array (
                'id' => 471,
                'tz' => 'Pacific/Johnston',
                'visible' => 0,
            ),
            471 => 
            array (
                'id' => 472,
                'tz' => 'Pacific/Kiritimati',
                'visible' => 0,
            ),
            472 => 
            array (
                'id' => 473,
                'tz' => 'Pacific/Kosrae',
                'visible' => 0,
            ),
            473 => 
            array (
                'id' => 474,
                'tz' => 'Pacific/Kwajalein',
                'visible' => 0,
            ),
            474 => 
            array (
                'id' => 475,
                'tz' => 'Pacific/Majuro',
                'visible' => 0,
            ),
            475 => 
            array (
                'id' => 476,
                'tz' => 'Pacific/Marquesas',
                'visible' => 0,
            ),
            476 => 
            array (
                'id' => 477,
                'tz' => 'Pacific/Midway',
                'visible' => 0,
            ),
            477 => 
            array (
                'id' => 478,
                'tz' => 'Pacific/Nauru',
                'visible' => 0,
            ),
            478 => 
            array (
                'id' => 479,
                'tz' => 'Pacific/Niue',
                'visible' => 0,
            ),
            479 => 
            array (
                'id' => 480,
                'tz' => 'Pacific/Norfolk',
                'visible' => 0,
            ),
            480 => 
            array (
                'id' => 481,
                'tz' => 'Pacific/Noumea',
                'visible' => 0,
            ),
            481 => 
            array (
                'id' => 482,
                'tz' => 'Pacific/Pago_Pago',
                'visible' => 0,
            ),
            482 => 
            array (
                'id' => 483,
                'tz' => 'Pacific/Palau',
                'visible' => 0,
            ),
            483 => 
            array (
                'id' => 484,
                'tz' => 'Pacific/Pitcairn',
                'visible' => 0,
            ),
            484 => 
            array (
                'id' => 485,
                'tz' => 'Pacific/Pohnpei',
                'visible' => 0,
            ),
            485 => 
            array (
                'id' => 486,
                'tz' => 'Pacific/Ponape',
                'visible' => 0,
            ),
            486 => 
            array (
                'id' => 487,
                'tz' => 'Pacific/Port_Moresby',
                'visible' => 0,
            ),
            487 => 
            array (
                'id' => 488,
                'tz' => 'Pacific/Rarotonga',
                'visible' => 0,
            ),
            488 => 
            array (
                'id' => 489,
                'tz' => 'Pacific/Saipan',
                'visible' => 0,
            ),
            489 => 
            array (
                'id' => 490,
                'tz' => 'Pacific/Samoa',
                'visible' => 0,
            ),
            490 => 
            array (
                'id' => 491,
                'tz' => 'Pacific/Tahiti',
                'visible' => 0,
            ),
            491 => 
            array (
                'id' => 492,
                'tz' => 'Pacific/Tarawa',
                'visible' => 0,
            ),
            492 => 
            array (
                'id' => 493,
                'tz' => 'Pacific/Tongatapu',
                'visible' => 0,
            ),
            493 => 
            array (
                'id' => 494,
                'tz' => 'Pacific/Truk',
                'visible' => 0,
            ),
            494 => 
            array (
                'id' => 495,
                'tz' => 'Pacific/Wake',
                'visible' => 0,
            ),
            495 => 
            array (
                'id' => 496,
                'tz' => 'Pacific/Wallis',
                'visible' => 0,
            ),
            496 => 
            array (
                'id' => 497,
                'tz' => 'Pacific/Yap',
                'visible' => 0,
            ),
        ));
        
        
    }
}