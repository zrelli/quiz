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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('tenant_id');//todo
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->on('questions')->references('id')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('order')->nullable();
            $table->text('description')->nullable();
            $table->text('explanation')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
