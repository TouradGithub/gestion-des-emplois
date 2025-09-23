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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('code')->nullable();
            $table->integer('confirm')->default(0);
            $table->string('email')->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('etat')->default(1);
            $table->rememberToken();
            $table->foreignId('sys_types_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('sys_types_users', function (Blueprint $table) {
            $table->id();
            $table->string('libelle', 120)->nullable();
            $table->string('libelle_ar', 120)->nullable();
            $table->integer('ordre')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

    }
};
