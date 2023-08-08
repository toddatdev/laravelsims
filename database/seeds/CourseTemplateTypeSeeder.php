<?php

use Illuminate\Database\Seeder;

class CourseTemplateTypeSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('resource_template_type')->delete();

        DB::table('resource_template_type')->insert(array (
            0 =>
            array (
                'name' => 'Specific',
            ),
            1 =>
            array (
                'name' => 'Category',
            ),
            2 =>
            array (
                'id' => 'Sub-Category',
            )
        ));


    }
}
