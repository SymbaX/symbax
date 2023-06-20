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
        Schema::create('event', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->varchar('name');
            $table->varchar('details');
            $table->varchar('category');
            $table->varchar('tag');
            $table->varchar('conditions_of_participation');
            $table->varchar('extarnal_links');
            $table->datetime('datetime');
            $table->varchar('place');
            $table->int('number_of_people');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
