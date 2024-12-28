<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_order_product_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('product_id');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
