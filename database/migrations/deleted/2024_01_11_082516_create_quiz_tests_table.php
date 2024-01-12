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
        Schema::create('quiz_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_quiz_id');
            $table->boolean('is_successful')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->unsignedInteger('spent_time')->nullable();
            $table->unsignedInteger('total_attempts')->nullable(); // has a value for  (in_time quiz) only
            $table->foreign('member_quiz_id')->references('id')->on('member_quizzes')->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_tests');
    }
};
