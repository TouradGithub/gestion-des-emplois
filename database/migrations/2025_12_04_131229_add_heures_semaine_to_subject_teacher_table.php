<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            // Weekly hours assigned for this teacher-class-trimester combination
            $table->decimal('heures_semaine', 5, 2)->nullable()->after('annee_id')->comment('Heures par semaine assignées');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->dropColumn('heures_semaine');
        });
    }
};
