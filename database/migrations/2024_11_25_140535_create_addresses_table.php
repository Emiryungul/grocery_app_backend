<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('marker'); // Marker for the address
            $table->string('address'); // Full address
            $table->decimal('lat', 10, 8); // Latitude
            $table->decimal('lon', 11, 8); // Longitude
            $table->string('name'); // Name for the address
            $table->string('post_code'); // Postal code
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
