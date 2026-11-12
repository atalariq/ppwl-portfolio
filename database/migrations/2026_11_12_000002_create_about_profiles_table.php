<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('role', 150);
            $table->string('summary', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('location', 150)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('github_url', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_profiles');
    }
};
