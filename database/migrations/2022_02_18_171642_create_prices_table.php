<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('product_id');

            $table->decimal('original_price', 10, 2, true);
            $table->decimal('sale_price', 10, 2, true)->nullable();
            $table->decimal('discount', 10, 2, true)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
