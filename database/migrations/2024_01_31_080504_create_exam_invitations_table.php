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
        Schema::create('exam_invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token')->max(60);
            $table->boolean('is_accepted')->nullable();// if score >= 70

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
        Schema::dropIfExists('exam_invitations');
    }
};
