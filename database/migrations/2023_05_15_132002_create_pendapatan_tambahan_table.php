<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendapatanTambahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendapatan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('period_code', 8)->nullable();
            $table->foreign('period_code')
                ->references('code')
                ->on('master_period')
                ->onDelete('cascade');
            $table->string('pju_bn', 16)->nullable();
            $table->foreign('pju_bn')
                ->references('pju_bn')
                ->on('man_power')
                ->onDelete('cascade');
            $table->string('name', 225); //Nama Pendapatan
            $table->decimal('amount', 9, 3); //Jumlah pendapatan tambahan
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
        Schema::dropIfExists('pendapatan_tambahan');
    }
}
