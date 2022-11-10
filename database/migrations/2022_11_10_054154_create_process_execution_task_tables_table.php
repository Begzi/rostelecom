<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessExecutionTaskTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_execution_task_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('string');
            $table->integer('repeated')->default(1);
            $table->integer('task_id')->unsigned();
            $table->integer('group_id')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_execution_task_tables');
    }
}
