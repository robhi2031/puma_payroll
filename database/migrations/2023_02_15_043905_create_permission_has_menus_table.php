<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_has_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 50)->nullable();
            $table->string('has_route', 10)->default('N');
            $table->string('route_name')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('has_child', 10)->default('N');
            $table->string('is_crud', 10)->default('N');
            $table->string('order_line', 10)->nullable();
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
        Schema::dropIfExists('permission_has_menus');
    }
};
