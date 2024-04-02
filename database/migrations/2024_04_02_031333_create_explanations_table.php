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
        Schema::create('explanations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('title_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_explanation_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('state', ['pending', 'approved', 'rejected', 'uploaded'])->default('pending');
            $table->string('video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explanations');
    }
};
