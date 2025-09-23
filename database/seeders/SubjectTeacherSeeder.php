<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectTeacherSeeder extends Seeder
{
    public function run()
    {
        $subjectTeachers = [
            // Ahmed Ali
            ['teacher_id' => 1, 'subject_id' => 1, 'annee_id' => 1, 'trimester_id' => 1], // رياضيات
            ['teacher_id' => 1, 'subject_id' => 2, 'annee_id' => 1, 'trimester_id' => 1], // فيزياء
            // Souad Mohamed
            ['teacher_id' => 2, 'subject_id' => 3, 'annee_id' => 1, 'trimester_id' => 1], // كيمياء
            // Youssef Omer
            ['teacher_id' => 3, 'subject_id' => 4, 'annee_id' => 1, 'trimester_id' => 1], // معلوماتية
        ];
        foreach ($subjectTeachers as $st) {
            DB::table('subject_teacher')->updateOrInsert([
                'teacher_id' => $st['teacher_id'],
                'subject_id' => $st['subject_id'],
                'annee_id' => $st['annee_id'],
                'trimester_id' => $st['trimester_id'],
            ], $st);
        }
    }
}
