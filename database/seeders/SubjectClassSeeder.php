<?php

namespace Database\Seeders;

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
        $subjects = Subject::whereIn('name', ['العلوم', 'الرياضيات', 'اللغة الإنجليزية', 'اللغة العربية'])->get();

        foreach ($classrooms as $classroom) {
            $classroom->subjects()->attach($subjects->pluck('id'));
        }

        // Ensure subjects like 'الفيزياء', 'الكيمياء', 'اللغة الفرنسية' exist in the database
        $physicsSubject = Subject::where('name', 'الفيزياء')->first();
        $chemistrySubject = Subject::where('name', 'الكيمياء')->first();
        $frenchSubject = Subject::where('name', 'اللغة الفرنسية')->first();

        // Attach 'الفيزياء', 'الكيمياء', 'اللغة الفرنسية' to classrooms 7 to 12
        for ($i = 6; $i < 12; $i++) {
            $classroom = $classrooms[$i];
            $classroom->subjects()->attach([
                $physicsSubject->id,
                $chemistrySubject->id,
                $frenchSubject->id,
            ]);
        }
    }
}
