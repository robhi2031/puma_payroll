<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->string('short_description', 225);
            $table->text('logo')->nullable();
            $table->text('profile')->nullable();
            $table->string('phone_number', 25)->nullable();
            $table->string('email')->nullable();
            $table->string('office_address')->nullable();
            $table->text('office_address_coordinate')->nullable();
            $table->string('npwp')->nullable();
            $table->string('no_jamsostek')->nullable();
            $table->unsignedBigInteger('fid_bank_account')->nullable(); // => ID=1 for Company Bank Account
            $table->foreign('fid_bank_account')
                ->references('id')
                ->on('bank_account')
                ->onDelete('cascade');
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
        Schema::dropIfExists('company');
    }
}
