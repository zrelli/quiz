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
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedInteger('time_taken')->default(0); // has a value for  (in_time quiz) only
            $table->unsignedTinyInteger('total_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->boolean('is_successful')->nullable();// if score >= 70
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
        Schema::dropIfExists('member_quizzes');
    }
};
