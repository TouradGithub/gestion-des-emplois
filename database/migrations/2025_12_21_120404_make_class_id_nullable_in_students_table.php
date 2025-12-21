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
        Schema::table('students', function (Blueprint $table) {
            // Drop existing foreign key constraint
            $table->dropForeign(['class_id']);

            // Make class_id nullable
            $table->unsignedBigInteger('class_id')->nullable()->change();

            // Re-add foreign key with SET NULL on delete
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['class_id']);

            // Make class_id required again
            $table->unsignedBigInteger('class_id')->nullable(false)->change();

            // Re-add foreign key with CASCADE on delete
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }
};
