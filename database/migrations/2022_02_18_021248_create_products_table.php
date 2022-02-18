<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('product_url')->unique();
            $table->string('product_code')->unique();

            $table->decimal('original_price', 10, 2, true);
            $table->decimal('sale_price', 10, 2, true)->nullable();
            $table->decimal('discount', 10, 2, true)->nullable();


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
