<?php

use Illuminate\Database\Seeder;

class ResourceIdentifierTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('resource_identifier_types')->delete();

        \DB::table('resource_identifier_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'abbrv' => 'resource',
                'created_at' => '2019-03-07 16:46:16',
                'updated_at' => '2019-03-07 16:46:16',
            ),
            1 =>
            array (
                'id' => 2,
                'abbrv' => 'category',
                'created_at' => '2019-03-07 16:46:16',
                'updated_at' => '2019-03-07 16:46:16',
            ),
            2 =>
            array (
                'id' => 3,
                'abbrv' => 'subcategory',
                'created_at' => '2019-03-07 16:46:16',
                'updated_at' => '2019-03-07 16:46:16',
            ),
        ));


    }
}
