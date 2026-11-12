<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category', 50)->default('Tools')->after('name');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->default(0)->after('name');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
