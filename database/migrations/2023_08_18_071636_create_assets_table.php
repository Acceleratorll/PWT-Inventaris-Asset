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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_type_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->string('item_code')->unique();
            $table->date('acquition');
            $table->boolean('isMoveable');
            $table->integer('total');
            $table->date('last_move_date')->nullable();
            $table->date('last_edit_date')->nullable();
            $table->enum('condition', ['good', 'bad']);
            $table->date('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};