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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('tenant_id');// todo we will add global scope to show only current tenant questions
            $table->unsignedBigInteger('quiz_id');
            $table->foreign('quiz_id')->on('quizzes')->references('id')->onDelete('cascade');
            $table->string('question');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_choices_randomly_ordered')->default(false);//shuffling items
            $table->boolean('has_multiple_answers')->default(false); // we will set all false
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
