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
            $table->unsignedInteger('average_spent_time')->nullable();// has a value for  (in_time quiz) only
            $table->unsignedInteger('total_attempts')->nullable();// has a value for  (in_time quiz) only
            $table->boolean('is_successful')->nullable();
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
                $table->unique(['member_id', 'quiz_id']);
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
