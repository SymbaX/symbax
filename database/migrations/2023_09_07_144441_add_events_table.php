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
            $table->string('image_path_b')->nullable(true)->after('image_path');
            $table->string('image_path_c')->nullable(true)->after('image_path_b');
            $table->string('image_path_d')->nullable(true)->after('image_path_c');
            $table->string('image_path_e')->nullable(true)->after('image_path_d');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('image_path','image_path_a');
        });
    }
};
