<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classe_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('classe_id');
            $table->unsignedBigInteger('annee_id');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('classe_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('annee_id')->references('id')->on('anneescolaires')->onDelete('cascade');

            // Unique constraint: a student can only be in one class per year
            $table->unique(['student_id', 'annee_id']);
        });

        // Migrate existing students data
        $students = DB::table('students')
            ->whereNotNull('class_id')
            ->get();

        foreach ($students as $student) {
            $classe = DB::table('classes')->where('id', $student->class_id)->first();
            if ($classe) {
                DB::table('classe_student')->insert([
                    'student_id' => $student->id,
                    'classe_id' => $student->class_id,
                    'annee_id' => $classe->annee_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_student');
    }
};
