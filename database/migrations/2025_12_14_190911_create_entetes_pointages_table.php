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
       Schema::create('entetes_pointages', function (Blueprint $table) {
            $table->id();

            $table->string('titre1', 120);
            $table->string('titre1_ar', 120);
            $table->boolean('afficher_titre1')->default(false)->comment('1:oui');

            $table->string('titre2', 120);
            $table->string('titre2_ar', 120);
            $table->boolean('afficher_titre2')->default(false)->comment('1:oui');

            $table->string('titre3', 120)->nullable();
            $table->string('titre3_ar', 120)->nullable();
            $table->boolean('afficher_titre3')->default(false)->comment('1:oui');

            $table->text('logo')->nullable();
            $table->string('devise')->nullable();
            $table->string('devise_ar')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('entetes_pointages')->insert([
            'titre1' => 'Institut Supérieur de Comptabilité et d’Administration des Entreprises',
            'titre1_ar' => 'المعهد العالي للمحاسبة وإدارة المؤسسات',
            'afficher_titre1' => true,

            'titre2' => 'Direction des Études',
            'titre2_ar' => 'إدارة الدروس',
            'afficher_titre2' => true,

            'titre3' => null,
            'titre3_ar' => null,
            'afficher_titre3' => false,

            'logo' => 'logo.png',
            'devise' => null,
            'devise_ar' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entetes_pointages');
    }
};
