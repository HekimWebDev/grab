<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->unique()->index();

            $table->tinyInteger('in_stock')->default(1);
            $table->tinyInteger('old_prices')->default(0);
            $table->string('name');
            $table->string('product_code')->unique();
            $table->integer('service_type');

            $table->string('category_name')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
