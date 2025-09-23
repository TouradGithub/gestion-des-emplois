<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubjectTeacher;
use App\Models\Classe;
use App\Models\Subject;

class UpdateSubjectTeacherWithClassIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // جلب جميع السجلات التي لا تحتوي على class_id
        $subjectTeachers = SubjectTeacher::whereNull('class_id')->get();

        $this->command->info('Found ' . $subjectTeachers->count() . ' records without class_id');

        foreach ($subjectTeachers as $subjectTeacher) {
            // البحث عن فصل مناسب بناءً على المادة
            $subject = Subject::find($subjectTeacher->subject_id);

            if ($subject && $subject->specialite_id) {
                // البحث عن أول فصل في نفس التخصص
                $classe = Classe::where('specialite_id', $subject->specialite_id)->first();

                if ($classe) {
                    $subjectTeacher->update(['class_id' => $classe->id]);
                    $this->command->info("Updated SubjectTeacher ID {$subjectTeacher->id} with class_id {$classe->id} ({$classe->nom})");
                } else {
                    // إذا لم يوجد فصل مناسب، استخدم أول فصل متاح
                    $firstClass = Classe::first();
                    if ($firstClass) {
                        $subjectTeacher->update(['class_id' => $firstClass->id]);
                        $this->command->info("Updated SubjectTeacher ID {$subjectTeacher->id} with first available class_id {$firstClass->id} ({$firstClass->nom})");
                    }
                }
            } else {
                // إذا لم توجد مادة أو تخصص، استخدم أول فصل متاح
                $firstClass = Classe::first();
                if ($firstClass) {
                    $subjectTeacher->update(['class_id' => $firstClass->id]);
                    $this->command->info("Updated SubjectTeacher ID {$subjectTeacher->id} with first available class_id {$firstClass->id} ({$firstClass->nom})");
                }
            }
        }

        $this->command->info('Successfully updated all SubjectTeacher records with class_id');
    }
}
