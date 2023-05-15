<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembalianPinjamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembalian_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('pju_bn', 16)->nullable();
            $table->foreign('pju_bn')
            ->references('pju_bn')
            ->on('man_power')
            ->onDelete('cascade');
            $table->string('tahun_pengembalian', 4);
            $table->date('tgl_pengembalian');
            $table->decimal('amount', 9, 3); //Jumlah Pengembalian
            $table->string('note')->nullable(); //Keterangan/ Catatan
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
        Schema::dropIfExists('pengembalian_pinjaman');
    }
}
