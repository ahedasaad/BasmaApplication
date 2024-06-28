<?php

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            ['name' => 'العلوم'],
            ['name' => 'الرياضيات'],
            ['name' => 'اللغة الإنجليزية'],
            ['name' => 'اللغة العربية'],
            ['name' => 'الفيزياء'],
            ['name' => 'الكيمياء'],
            ['name' => 'اللغة الفرنسية'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
