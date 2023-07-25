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
        Schema::table('operation_logs', function (Blueprint $table) {
            $table->after('user_id', function ($table) {
                $table->string('target_event_id')->nullable();
                $table->string('target_user_id')->nullable();
                $table->string('target_topic_id')->nullable();
            });
        });

        Schema::table('operation_logs', function (Blueprint $table) {
            $table->string('action', 256)->change();
            $table->string('detail', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_logs', function (Blueprint $table) {
            $table->string('action', 500)->change();
            $table->dropColumn('detail');
        });

        Schema::table('operation_logs', function (Blueprint $table) {
            $table->dropColumn('target_event_id');
            $table->dropColumn('target_user_id');
            $table->dropColumn('target_topic_id');
        });
    }
};
