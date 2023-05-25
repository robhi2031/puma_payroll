<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManPowerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_power', function (Blueprint $table) {
            $table->id();
            $table->string('pju_bn', 16);
            $table->string('ext_bn', 16);
            $table->string('name', 225);
            $table->string('email', 225);
            $table->string('project_code', 8);
            $table->string('jobposition_code', 8);
            $table->string('department', 225);
            $table->string('npwp', 100)->nullable();
            $table->string('kpj', 100)->nullable();
            $table->string('kis', 100)->nullable();
            $table->string('marital_status', 100);
            $table->string('shift_code', 50);
            $table->string('pay_code', 50);
            $table->string('shift_group', 150)->nullable();
            $table->string('is_daily', 1)->default('N');
            $table->decimal('daily_basic', 9, 3)->nullable();
            $table->decimal('basic_salary', 9, 3);
            $table->string('ot_rate', 50);
            $table->decimal('attendance_fee', 9, 3)->nullable();
            $table->decimal('leave_day', 9, 3)->nullable();
            $table->decimal('premi_sore', 9, 3)->nullable();
            $table->decimal('premi_malam', 9, 3)->nullable();
            $table->decimal('thr', 9, 3)->nullable();
            $table->decimal('transport', 9, 3)->nullable();
            $table->decimal('uang_cuti', 9, 3)->nullable();
            $table->decimal('uang_makan', 9, 3)->nullable();
            $table->decimal('bonus', 9, 3)->nullable();
            $table->decimal('interim_location', 9, 3)->nullable();
            $table->decimal('tunjangan_jabatan', 9, 3)->nullable();
            $table->decimal('p_biaya_fasilitas', 9, 3)->nullable();
            $table->decimal('pengobatan', 9, 3)->nullable();
            $table->string('work_status', 100);
            $table->unsignedBigInteger('fid_bank_account')->nullable();
            $table->foreign('fid_bank_account')
                ->references('id')
                ->on('bank_account')
                ->onDelete('cascade');
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
        Schema::dropIfExists('man_power');
    }
}
