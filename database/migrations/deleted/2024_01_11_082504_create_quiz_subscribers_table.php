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
        Schema::create('quiz_subscribers', function (Blueprint $table) {
            // $table->id();
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
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_subscribers');
    }
};
