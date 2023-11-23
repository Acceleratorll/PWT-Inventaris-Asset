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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained();
            $table->foreignId('condition_id')->constrained();
            $table->unsignedBigInteger('from_room_id');
            $table->unsignedBigInteger('to_room_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('from_room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('to_room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
