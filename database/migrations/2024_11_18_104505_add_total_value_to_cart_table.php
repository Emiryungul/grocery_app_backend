<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalValueToCartTable extends Migration
{
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('total_value', 10, 2)->default(0); // Add total_value column with default 0
        });
    }

    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('total_value'); // Remove total_value column on rollback
        });
    }
}

