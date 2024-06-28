<?php

use Illuminate\Database\Seeder;
use App\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = [
            ['name' => 'الصف الأول'],
            ['name' => 'الصف الثاني'],
            ['name' => 'الصف الثالث'],
            ['name' => 'الصف الرابع'],
            ['name' => 'الصف الخامس'],
            ['name' => 'الصف السادس'],
            ['name' => 'الصف السابع'],
            ['name' => 'الصف الثامن'],
            ['name' => 'الصف التاسع'],
            ['name' => 'الصف العاشر'],
            ['name' => 'الصف الحادي عشر'],
            ['name' => 'الصف الثاني عشر'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
