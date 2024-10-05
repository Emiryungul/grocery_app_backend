<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the product
            $table->text('description')->nullable(); // Optional description of the product
            $table->decimal('price', 10, 2); // Price of the product
            $table->string('image_path')->nullable(); // Image of the product
            $table->unsignedBigInteger('category_id'); // Foreign key to categories
            $table->timestamps(); // Timestamps for created_at and updated_at

            // Define the foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}


