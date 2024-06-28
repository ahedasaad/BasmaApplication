<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Subject;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

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
