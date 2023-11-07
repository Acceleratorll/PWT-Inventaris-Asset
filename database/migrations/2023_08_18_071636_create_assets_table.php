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
            // $table->foreignId('room_id')->constrained();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->date('acquition');
            $table->integer('total');
            $table->datetime('last_move_date')->nullable();
            $table->enum('condition', ['good', 'bad']);
            $table->text('note')->nullable();
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
