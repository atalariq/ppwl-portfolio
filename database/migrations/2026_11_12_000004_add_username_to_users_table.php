<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
        });

        $used = [];

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            $base = Str::slug(Str::before((string) $user->email, '@')) ?: 'user-'.$user->id;
            $username = $base;
            $suffix = 2;

            while (in_array($username, $used, true)) {
                $username = $base.'-'.$suffix;
                $suffix++;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
            $used[] = $username;
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
