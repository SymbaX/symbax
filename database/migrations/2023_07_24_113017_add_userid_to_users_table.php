<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_id')->after('id');
        });

        // Set initial unique values for the login_id
        DB::table('users')->orderBy('id')->each(function ($user, $index) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['login_id' => 'login_' . $index]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login_id');
        });
    }
};
