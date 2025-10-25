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
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emploi_temps_id');
            $table->unsignedBigInteger('teacher_id');
            $table->date('date_pointage');
            $table->enum('statut', ['present', 'absent'])->default('absent');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->text('remarques')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('emploi_temps_id')->references('id')->on('emplois_temps')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // Index pour améliorer les performances
            $table->index(['teacher_id', 'date_pointage']);
            $table->index(['emploi_temps_id', 'date_pointage']);

            // Contrainte unique pour éviter les doublons
            $table->unique(['emploi_temps_id', 'date_pointage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
