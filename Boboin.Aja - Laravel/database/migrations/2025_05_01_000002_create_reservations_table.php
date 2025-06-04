<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->date('guest_dob')->nullable();
            $table->unsignedBigInteger('room_id');
            $table->string('room_name');
            $table->string('room_number');
            $table->integer('person');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('duration');
            $table->boolean('early_checkin')->default(false);
            $table->boolean('late_checkout')->default(false);
            $table->boolean('extra_bed')->default(false);
            $table->text('other_request')->nullable();
            $table->decimal('base_price', 15, 2);
            $table->decimal('request_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('Pending');
            $table->timestamps();
            
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};