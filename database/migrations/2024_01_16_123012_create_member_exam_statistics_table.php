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
        Schema::create('member_exam_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('current_question_index')->default(0);//  (0 to 180) minutes
            $table->unsignedBigInteger('member_quiz_id');
            $table->string('questions_data'); // Array [0,1,0,1,0,...] correct  or not correct answer
            $table->unsignedTinyInteger('time_taken')->default(0);//  (0 to 180) minutes
            $table->unsignedInteger('average_score')->default(0);
//            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->foreign('member_quiz_id')
            ->references('id')
            ->on('member_quizzes') 
            ->onDelete('cascade');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_exam_statistics');
    }
};
