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
        Schema::create('niveau_pedagogiques', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('ordre')->nullable();
            $table->unsignedInteger('formation_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_pedagogiques');
    }
};
