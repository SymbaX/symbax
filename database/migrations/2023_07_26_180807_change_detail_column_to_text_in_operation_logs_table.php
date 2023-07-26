<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDetailColumnToTextInOperationLogsTable extends Migration
{
    public function up()
    {
        Schema::table('operation_logs', function (Blueprint $table) {
            $table->text('detail')->change();
        });
    }

    public function down()
    {
        Schema::table('operation_logs', function (Blueprint $table) {
            $table->string('detail', 1000)->change();
        });
    }
}
