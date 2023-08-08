<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'first_name' => 'SIMS',
                'middle_name' => 'B.',
                'last_name' => 'Administrator',
            'phone' => '(412)555-1212',
                'email' => 'wiserhelp2@upmc.edu',
                'password' => '$2y$10$t04XVF/czNXfLdXnwEhjOu2tpKArxbfNM36mu2GZUBF0A7TDdDKAS',
                'status' => 1,
                'confirmation_code' => '02a2223230014c5162961da4532e8ca2',
                'confirmed' => 1,
                'remember_token' => 'GbH1GMUX3KkA0Ul3N2MeiEoGvIAmwikNIbgVKkWtNms2IL0jFBpd3PMV71F2',
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'first_name' => 'John',
                'middle_name' => '',
                'last_name' => 'Lutz',
            'phone' => '(412)555-1212',
                'email' => 'lutzjw@gmail.com',
                'password' => '$2y$10$UtM3zSzvBRbJXgNwybvqHOjrajCivqL/cWeGanTBYmYyQuy1jh5cK',
                'status' => 1,
                'confirmation_code' => 'f0426929c406c0c95d95d54a687d9de7',
                'confirmed' => 1,
                'remember_token' => 'xRKBsnKWs166O4trYjuj4QvHZesDWYtFKMXT360X9LdM5QnD7HzJGjJFgDvR',
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'first_name' => 'Default',
                'middle_name' => 'Test',
                'last_name' => 'User',
            'phone' => '(412)555-1212',
                'email' => 'development.simmedical@gmail.com',
                'password' => '$2y$10$LUnav6XAkjk/nd.zJjLn2eS5eCKSuoUuVelDkRn.ji969nVz6ZNMi',
                'status' => 1,
                'confirmation_code' => 'bd0f06e7f8d8638ac7ec5be90ba63cf9',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'first_name' => 'Kimberly',
                'middle_name' => 'S',
                'last_name' => 'Mitchell',
            'phone' => '(412)555-1212',
                'email' => 'mitchellks@upmc.edu',
                'password' => '$2y$10$BsPNir56AnxaQ6bSQVBaDeQLjcLjlUXej0lvYpi.WMsm1vRfEeEFa',
                'status' => 1,
                'confirmation_code' => '25ff3ae2ccca1bfbd966b05e1bfd3b6c',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:12:14',
                'updated_at' => '2018-03-21 14:12:14',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'Building',
                'phone' => '412-555-1212',
                'email' => 'lutzjw+building@gmail.com',
                'password' => '$2y$10$xO2Q45RUH6Rbjk2QPX9UmO.0k/9uYBXchY.8XjFOBAmPDcJyt0f5y',
                'status' => 1,
                'confirmation_code' => 'a18d0fa5f8c8ccd75e0acbd0becb505e',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:39:27',
                'updated_at' => '2018-03-21 14:39:27',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'Location',
                'phone' => '412-555-1212',
                'email' => 'lutzjw+location@gmail.com',
                'password' => '$2y$10$iLK52sns3zv071qwdDSx5uQU4bkLhQ3yjQD2LwH86n0vW7SPubIsS',
                'status' => 1,
                'confirmation_code' => '9719ae658b56ec3e479fe932f65dd912',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:40:12',
                'updated_at' => '2018-03-21 14:40:12',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'Course',
                'phone' => '412-555-1212',
                'email' => 'lutzjw+course@gmail.com',
                'password' => '$2y$10$zx7VlcA7GGo1W2FKSI1xxuYWHW9Iw8dMkH.YXPQC.SSPtKc6JVXha',
                'status' => 1,
                'confirmation_code' => '24bfc14a8771108cc58cf4beee443fce',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:41:23',
                'updated_at' => '2018-03-21 14:41:23',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'User',
                'phone' => '412-555-1212',
                'email' => 'lutzjw+user@gmail.com',
                'password' => '$2y$10$DjJxK7Qp0mkmkKOn9ZYhVuUay.ck/ui2xyZwdS/ZXA2yeU15oWMES',
                'status' => 1,
                'confirmation_code' => '37202ad9af2470afad1f576a536a5971',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 14:42:31',
                'updated_at' => '2018-03-21 14:42:44',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'SimTiki',
                'phone' => '808-555-1212',
                'email' => 'lutzjw+simtiki@gmail.com',
                'password' => '$2y$10$0.9avdXQqd.Sv7ekQC3jHOxWsP1VIujo9kEvI0W6Ml7oYkFdZqJym',
                'status' => 1,
                'confirmation_code' => 'd505edf9659f6f40012015655ddb2965',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-21 17:40:49',
                'updated_at' => '2018-03-21 17:40:49',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'first_name' => 'Test',
                'middle_name' => 'T',
                'last_name' => 'Role',
                'phone' => '412-555-1212',
                'email' => 'lutzjw+role@gmail.com',
                'password' => '$2y$10$Td83RDFRX9TQN2K.k.UuJe5ntSjVaNJVNBIhsV6oLw9dqWIw4i8Pi',
                'status' => 1,
                'confirmation_code' => '3e3b998a80f71effff7e1eaedbcf97f6',
                'confirmed' => 1,
                'remember_token' => 'BA5E04OEDZ4jZgdjQ6xPCnhrQvlgH6JkZEoci9t6VmxpT8JJpboRHl5QC1hy',
                'created_at' => '2018-03-26 21:34:06',
                'updated_at' => '2018-03-29 13:50:51',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'first_name' => 'Test',
                'middle_name' => NULL,
                'last_name' => 'UserManager',
                'phone' => NULL,
                'email' => 'lutzjw+usermanager@gmail.com',
                'password' => '$2y$10$KjaRFcxTnGtd4ViH5SzKAuQlt/mYUuEhfKcj8MVDyUUvEDZHFp9cK',
                'status' => 1,
                'confirmation_code' => 'b28a858ede388333773b20a7bddc1f36',
                'confirmed' => 1,
                'remember_token' => '1gIupPwhQmro5y7XleN6szPFj2B3JGvcMr9BIBHvspcT3InRcXnubSQmiCWU',
                'created_at' => '2018-03-29 14:42:49',
                'updated_at' => '2018-03-29 14:47:26',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 42,
                'first_name' => 'Test',
                'middle_name' => NULL,
                'last_name' => 'Kanaka',
                'phone' => NULL,
                'email' => 'lutzjw+kanaka@gmail.com',
                'password' => '$2y$10$vx9B14f5FhRm892ccoP4Z.70o1QGpI3O6MdAKv52dVhTw3osaoqzy',
                'status' => 1,
                'confirmation_code' => 'b0cfd9ba8fa000a0024a4bf0fd417397',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-29 19:41:49',
                'updated_at' => '2018-03-29 19:58:52',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 43,
                'first_name' => 'Test',
                'middle_name' => NULL,
                'last_name' => 'User',
                'phone' => NULL,
                'email' => 'lutzjw+test@gmail.com',
                'password' => '$2y$10$reqX/FtL8ijoSj7rQLU6aekhmDLKxw1EHTBD7P9fMNHuYK91.A.1u',
                'status' => 1,
                'confirmation_code' => '34de41a66a817094cc86b0f39ea82a89',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2018-03-29 19:59:53',
                'updated_at' => '2018-03-29 19:59:53',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 44,
                'first_name' => 'Testy',
                'middle_name' => NULL,
                'last_name' => 'McTestFace',
                'phone' => NULL,
                'email' => 'lutzjw+testy@gmail.com',
                'password' => '$2y$10$pBhtouqx08G38e9hH2sSm.1u8qYNwcyPk8jVo3j0fSdEKcLjhL2oq',
                'status' => 1,
                'confirmation_code' => '6b9f825908d8e594114e1734cf1c424e',
                'confirmed' => 1,
                'remember_token' => 'TYbDVDb5HSSxrQBosldUUmOjDMR7wwq7Q8jvPbnbizw1Ag0SkoDPXrXQcufY',
                'created_at' => '2018-03-29 21:03:25',
                'updated_at' => '2018-03-30 18:27:04',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 45,
                'first_name' => 'Test',
                'middle_name' => NULL,
                'last_name' => 'Captcha',
                'phone' => NULL,
                'email' => 'lutzjw+captcha@gmail.com',
                'password' => '$2y$10$d0n6tf06XHFFoc1ui2Z/.u7OWC0L6sru31OIS27XF3LigsE7EoSXK',
                'status' => 1,
                'confirmation_code' => '1a5ec38ef788915b085e2e95dcd4f9e5',
                'confirmed' => 1,
                'remember_token' => 'zuipyFl5upUNWbofVY2nk1uP1gZLXVpLZ6XEyBleoEaxsR9pkGP8OWEAKU9D',
                'created_at' => '2018-03-30 16:30:29',
                'updated_at' => '2018-03-30 16:32:40',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 46,
                'first_name' => 'Johnny',
                'middle_name' => NULL,
                'last_name' => 'NoPerms',
                'phone' => NULL,
                'email' => 'lutzjw+noperm@gmail.com',
                'password' => '$2y$10$TnzzcvnSIMRmUC30vlub3O1bPkYfrhb.rGPevHGLia5elmlBclLBW',
                'status' => 1,
                'confirmation_code' => '9b77fabb71db2850ca81212622ff32f5',
                'confirmed' => 0,
                'remember_token' => 'lDxhYLkOtEvUf256HQbvGwRSNSSBJ3hV222D4GFRAbWz8NIwgWPlT7gQWVq4',
                'created_at' => '2018-04-24 19:56:24',
                'updated_at' => '2018-04-24 19:56:24',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 47,
                'first_name' => 'Johnny',
                'middle_name' => NULL,
                'last_name' => 'NoPerms',
                'phone' => '412-123-4567',
                'email' => 'lutzjw+noperms@gmail.com',
                'password' => '$2y$10$gkHKA76uwwzRpSY3j5xO0OXPt/sJaRJj6jl439bTrRbh4rK1TrlK.',
                'status' => 1,
                'confirmation_code' => '1d42342e22ad75b922511c04e34c76bd',
                'confirmed' => 1,
                'remember_token' => 'Z8uECgAZITZAEGgN875H4oPyfZMxgCByG3nNdvVtdMeXnQUanqdwyxGHMZ9S',
                'created_at' => '2018-04-24 20:06:37',
                'updated_at' => '2018-04-24 20:08:06',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 49,
                'first_name' => 'Schedule Requester',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+schedulerequestonly@gmail.com',
                'password' => '$2y$10$yQp2rCxV7c3hUPGlWo83.uuRTKMZVJp4bXjAn.2BKgKWk2WVx1D4W',
                'status' => 1,
                'confirmation_code' => 'dc282b8ccd27a03d458d399982608b4a',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-04 16:03:24',
                'updated_at' => '2019-04-04 16:49:59',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 50,
                'first_name' => 'Schedule Manager',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+schedulemanageronly@gmail.com',
                'password' => '$2y$10$7ngYOLjIQFu3ih23Iz2FwOXBTe7FNAP1I10/vMLAVv/8dDew.gJai',
                'status' => 1,
                'confirmation_code' => 'e847f9b4353d1bf57ae9ceb8defebe41',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-04 16:04:00',
                'updated_at' => '2019-04-04 16:50:18',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 51,
                'first_name' => 'Test',
                'middle_name' => NULL,
                'last_name' => 'No Role',
                'phone' => NULL,
                'email' => 'test@nowhere.com',
                'password' => '$2y$10$zH594DPXh8QICk7E2ia6GOjG7bNOWzdW7bxQUxc0U6NWNjuulv7vq',
                'status' => 1,
                'confirmation_code' => '770c436604c4ec5eaad443acbb180e94',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-04 17:07:21',
                'updated_at' => '2019-04-04 17:07:21',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 52,
                'first_name' => 'Template Manager',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+templates@gmail.com',
                'password' => '$2y$10$b2oIMZXpVVaWeG6h/NgVLem3BIv4yu9UvQTJ4iyz/xOULkXzraWGm',
                'status' => 1,
                'confirmation_code' => '36c8bc51d846c0281802acd2d3561185',
                'confirmed' => 1,
                'remember_token' => 'A35lVQj2G2NNQxpZ5zohp878MP04uQbW252RtOpX7WUeYyYZkykh5TzJ2s5G',
                'created_at' => '2019-04-05 14:46:54',
                'updated_at' => '2019-04-05 14:46:54',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 53,
                'first_name' => 'Course Manager',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+coursemanageronly@gmail.com',
                'password' => '$2y$10$K5DvxtVn5CpiJYWAeVLmw.uNmD5zl09No4h69H6quKqCKwc65A55O',
                'status' => 1,
                'confirmation_code' => 'cf867c33f307408450a0aeeb0011cfcc',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-05 16:21:14',
                'updated_at' => '2019-04-05 16:21:14',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 54,
                'first_name' => 'Course Options',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+courseoptionsonly@gmail.com',
                'password' => '$2y$10$mvTBlXBE16UcK/ICGmTt..r5PeD1P0LQK7WWHjwpxBtkMHmT793da',
                'status' => 1,
                'confirmation_code' => '07bb5072b2ce03530ca441acd6ced13b',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-05 18:39:37',
                'updated_at' => '2019-04-05 18:42:35',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 55,
                'first_name' => 'Course Categories',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+coursecatsonly@gmail.com',
                'password' => '$2y$10$Er7WpR.Gz.qCzuVaNLjTfuS.UFyXIhCvqvC.mWwCHQYeCh07hRcdm',
                'status' => 1,
                'confirmation_code' => 'bddd1ffda99ecdbb0dec6136287e1585',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-05 18:42:18',
                'updated_at' => '2019-04-05 18:42:18',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 56,
                'first_name' => 'Resource Manager',
                'middle_name' => NULL,
                'last_name' => 'Only',
                'phone' => NULL,
                'email' => 'lutzjw+resourcemanageronly@gmail.com',
                'password' => '$2y$10$cP.Zm4Fh3mKy03qL38AKv.15di9T2jTHigrwtA4eg9dEOVV2IrCUq',
                'status' => 1,
                'confirmation_code' => '9e5633ab20133c53ff7a49c0f1bee411',
                'confirmed' => 1,
                'remember_token' => NULL,
                'created_at' => '2019-04-22 18:24:08',
                'updated_at' => '2019-04-22 18:24:08',
                'deleted_at' => NULL,
            ),
            25 =>
                array (
                    'id' => 57,
                    'first_name' => 'Henrik',
                    'middle_name' => NULL,
                    'last_name' => 'Nordstrom',
                    'phone' => NULL,
                    'email' => 'henrik@webtractive.com',
                    'password' => '$2y$10$GSjC/hPM8tnIYguxLjnW1.HUrHbZdmeccwo2nFP8Z5lwIdkEuHJ4q',
                    'status' => 1,
                    'confirmation_code' => '9e5633ab20133c53ff7a49c0f1bee411',
                    'confirmed' => 1,
                    'remember_token' => NULL,
                    'created_at' => '2020-05-11 18:24:08',
                    'updated_at' => '2020-05-11 18:24:08',
                    'deleted_at' => NULL,
                ),
        ));



        
    }
}