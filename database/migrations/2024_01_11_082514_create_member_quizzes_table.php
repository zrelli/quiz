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
        Schema::create('member_quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('average_score')->nullable();
            $table->unsignedInteger('average_spent_time')->nullable(); // has a value for  (in_time quiz) only
            $table->unsignedInteger('total_attempts')->nullable(); // has a value for  (in_time quiz) only
            $table->boolean('is_successful')->nullable();
            //reset all attempt values after each test attempt (expired or completed or failed or succeeded)
            $table->unsignedInteger('current_attempt_score')->nullable();
            $table->unsignedInteger('current_attempt_spent_time')->nullable();
            $table->unsignedInteger('current_attempt_question')->nullable();
            $table->timestamp('current_attempt_start_at')->nullable();
            $table->timestamp('current_attempt_end_at')->nullable();
            $table->boolean('current_attempt_is_closed')->default(false);
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');
            $table->unsignedBigInteger('quiz_id');
            $table->foreign('quiz_id')
                ->references('id')
                ->on('quizzes')
                ->onDelete('cascade');
            $table->unique(['member_id', 'quiz_id']); // uncommented to avoid keys duplication error (dev)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
