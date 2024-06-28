<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Title;
use App\Models\SubjectClass;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all subject classes
        $subjectClasses = SubjectClass::all();

        // Define logical titles in Arabic for each subject
        $titles = [
            'الأعداد',
            'جسم الأنسان',
            'الأحرف الإنجليزية',
            'الأحرف العربية',

            'الجمع والطرح',
            'الجهاز التنفسي',
            'الأسماء بالانجليزية',
            'الأسماء',

            'الضرب والقسمة',
            'الجهاز الهضمي',
            'الأفعال بالانجليزية ',
            'الأفعال بالعربية',

            'الاعداد الأولية',
            'الخلية',
            'الجمل بالانجليزية',
            'المبتدأ والخبر',

        ];

        // Counter for titles
        $titleIndex = 0;

        // Loop through each subject class and create titles
        foreach ($subjectClasses as $subjectClass) {
            if ($titleIndex < count($titles)) {
                Title::create([
                    'subject_class_id' => $subjectClass->id,
                    'name' => $titles[$titleIndex],
                ]);
                $titleIndex++;
            }
        }
    }
}
