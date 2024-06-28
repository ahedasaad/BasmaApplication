<?php

use Illuminate\Database\Seeder;
use App\Models\SubjectClass;
use App\Models\Classroom;
use App\Models\Subject;

class SubjectClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();

        // ربط الصفوف من الأول إلى السادس بالمواد: العلوم، الرياضيات، اللغة الإنجليزية، اللغة العربية
        $classrooms = Classroom::all();
        $subjects = Subject::whereIn('name', ['العلوم', 'الرياضيات', 'اللغة الإنجليزية', 'اللغة العربية'])->get();

        foreach ($classrooms as $classroom) {
            $classroom->subjects()->attach($subjects->pluck('id'));
        }

        // ربط الصفوف من السابع إلى الثاني عشر بالمواد: الفيزياء، الكيمياء، اللغة الفرنسية
        for ($i = 6; $i < 13; $i++) {
            $classroom = $classrooms[$i];
            $classroom->subjects()->attach([
                $subjects[4]->id, // الفيزياء
                $subjects[5]->id, // الكيمياء
                $subjects[6]->id, // اللغة الفرنسية
            ]);
        }
    }
}
