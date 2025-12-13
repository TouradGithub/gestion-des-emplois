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
        // Create subject_types table
        Schema::create('subject_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // TD, TP, Project
            $table->string('code')->unique(); // td, tp, project
            $table->string('color')->nullable(); // For UI display
            $table->string('icon')->nullable();  // FontAwesome icon
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Insert default types BEFORE adding foreign key
        DB::table('subject_types')->insert([
            ['id' => 1, 'name' => 'TD', 'code' => 'td', 'color' => '#28a745', 'icon' => 'fas fa-chalkboard', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'TP', 'code' => 'tp', 'color' => '#007bff', 'icon' => 'fas fa-flask', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Project', 'code' => 'project', 'color' => '#fd7e14', 'icon' => 'fas fa-project-diagram', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Add subject_type_id to subjects table (default 1 = TD)
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_type_id')->default(1)->after('specialite_id');
            $table->foreign('subject_type_id')->references('id')->on('subject_types')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['subject_type_id']);
            $table->dropColumn('subject_type_id');
        });

        Schema::dropIfExists('subject_types');
    }
};
