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
        Schema::table('events', function (Blueprint $table) {
            //
            $table->dropColumn('tag');
            $table->dropColumn('participation_condition');
            $table->dropColumn('external_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
            $table->string('tag');
            $table->string('participation_condition');
            $table->string('external_link');
        });
    }
};
