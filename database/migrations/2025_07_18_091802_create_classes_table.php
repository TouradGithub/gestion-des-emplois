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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // example: L1 Info A
//            $table->foreignId('niveau_pedagogique_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('niveau_pedagogique_id');
            $table->unsignedBigInteger('specialite_id');
            $table->unsignedBigInteger('annee_id');
//            $table->foreign('annee_id')->references('id')->on('anneescolaires')->onDelete('cascade');
//            $table->foreign('niveau_pedagogique_id')->references('id')->on('niveau_pedagogiques')->onDelete('cascade');
//            $table->foreign('niveau_pedagogique_id')->references('id')->on('specialites')->onDelete('cascade');
           // academic year
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
