<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('pwinty_id');
            $table->string('pwinty_status');
            $table->timestamps();

            $table->index(['user_id', 'pwinty_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
