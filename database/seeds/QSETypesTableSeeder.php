<?php

use App\Models\CourseContent\QSE\QSEType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QSETypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentTimestamp = Carbon::now();
        $qseTypes = [
            [
                'id' => 1,
                'description' => 'Quiz',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'id' => 2,
                'description' => 'Survey',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'id' => 3,
                'description' => 'Evaluation of Course',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'id' => 4,
                'description' => 'Evaluation of Person',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ]
        ];

        foreach ($qseTypes as $type) {
            $QSEType = QSEType::find($type['id']);

            if ($QSEType) {
                $QSEType->update($type);
            } else {
                QSEType::create($type);
            }
        }
    }
}
