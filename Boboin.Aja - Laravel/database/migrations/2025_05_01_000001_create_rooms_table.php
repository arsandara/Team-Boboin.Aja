<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->string('room_name');
            $table->string('room_number');
            $table->enum('room_type', ['standard', 'jacuzzi', 'family', 'pet_friendly', 'romantic'])->default('standard');
            $table->decimal('price', 12, 2);
            $table->boolean('is_available')->default(true);
            $table->integer('capacity')->default(2);
            $table->decimal('rating', 3, 1)->default(0);
            $table->tinyInteger('breakfast_included')->default(1); 
            $table->string('image_booking')->nullable();
            $table->string('image_room')->nullable();
            $table->integer('total_rooms')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rooms');
    }
};
