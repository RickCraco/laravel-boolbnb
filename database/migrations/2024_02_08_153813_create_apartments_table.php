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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('rooms', 2);
            $table->integer('beds', 2);
            $table->integer('bathrooms', 2);
            $table->integer('square_meters', 5);
            $table->text('address');
            $table->string('cover_img')->nullable();
            $table->string('slug');
            $table->boolean('visible');
            $table->decimal('lat', 9,6);
            $table->decimal('lon', 9,6);
            $table->unsignedBigInteger('user_id')->after('id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
