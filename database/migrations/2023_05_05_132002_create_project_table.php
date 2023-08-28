<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8);
            $table->string('name', 225);
            $table->string('desc', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('client', 255);
            $table->date('start_date');
            $table->date('end_date');
            // $table->string('status', 50)->default('Not Started'); //Not Started || In Progress || Completed || Stop
            $table->tinyInteger('status', 1)->default(0); //1 => Aktif; 0 => Tidak Aktif
            $table->integer('user_add');
            $table->integer('user_updated')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('NULL on update CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project');
    }
}
