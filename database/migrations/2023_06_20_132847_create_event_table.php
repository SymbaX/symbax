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
            $table->string('name');
            $table->string('details',1000);
            $table->string('category');
            $table->string('tag');
            $table->string('conditions_of_participation');
            $table->string('extarnal_links');
            $table->datetime('datetime');
            $table->string('place');
            $table->integer('number_of_people');
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