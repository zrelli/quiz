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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('max_attempts')->default(1);
            $table->unsignedInteger('duration')->default(1); //HOURS
            $table->enum('test_type', ['in_time', 'out_of_time'])->default('out_of_time');
            $table->timestamp('expired_at')->default(now());
            $table->timestamp('started_at')->default(now());
            //if is not out of time it should have a quiz_period_time record
            // $table->boolean('is_out_of_time')->default(true);//to delete
            $table->boolean('is_expired')->default(false);//to delete 
            $table->timestamps();
            $table->foreign('tenant_id')
            ->references('id')
            ->on('tenants')
            ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
