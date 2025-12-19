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
            $table->renameColumn('heures_semaine', 'heures_trimestre');
        });

        // Mettre à jour le commentaire de la colonne
        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->decimal('heures_trimestre', 5, 2)->nullable()->comment('Total heures de la matière pour le trimestre')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->renameColumn('heures_trimestre', 'heures_semaine');
        });

        Schema::table('subject_teacher', function (Blueprint $table) {
            $table->decimal('heures_semaine', 5, 2)->nullable()->comment('Heures par semaine assignées')->change();
        });
    }
};
