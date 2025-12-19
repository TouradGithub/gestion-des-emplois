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
        Schema::table('teacher_requests', function (Blueprint $table) {
            // Supprimer les anciennes contraintes
            $table->dropForeign(['jour_id']);
            $table->dropForeign(['horaire_id']);
        });

        Schema::table('teacher_requests', function (Blueprint $table) {
            // Ajouter les nouvelles contraintes avec les bons noms de tables
            $table->foreign('jour_id')->references('id')->on('sct_refjours')->onDelete('cascade');
            $table->foreign('horaire_id')->references('id')->on('sct_ref_horaires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->dropForeign(['jour_id']);
            $table->dropForeign(['horaire_id']);
        });

        Schema::table('teacher_requests', function (Blueprint $table) {
            $table->foreign('jour_id')->references('id')->on('jours')->onDelete('cascade');
            $table->foreign('horaire_id')->references('id')->on('horaires')->onDelete('cascade');
        });
    }
};
