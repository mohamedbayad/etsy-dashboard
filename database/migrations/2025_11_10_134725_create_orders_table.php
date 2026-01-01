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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->string('image_path')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('status')->default('main_time');
            $table->integer('main_days_allocated');
            $table->integer('extra_days_allocated');
            $table->integer('days_spent_main')->default(0);
            $table->integer('days_spent_extra')->default(0);
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
        Schema::dropIfExists('orders');
    }
};
