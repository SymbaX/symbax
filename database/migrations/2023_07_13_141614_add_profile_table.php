<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('self_introduction')->nullable()->after('department_id');
            $table->string('profile_photo_path')->nullable()->default('デフォルトの自己紹介文')->after('self_introduction');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('self_introduction');
            $table->dropColumn('profile_photo_path');
        });
    }
};
