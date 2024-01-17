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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('average_score')->nullable();
            $table->unsignedInteger('average_spent_time')->nullable(); // has a value for  (in_time quiz) only
            $table->unsignedInteger('total_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->unsignedInteger('successful_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->unsignedInteger('failed_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->string('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            //todo:for auth guard
            $table->unique(['tenant_id', 'user_id']);// commented to avoid keys duplication error (dev)
            $table->timestamps();
            //todo for auth member guard
            $table->boolean('is_choices_randomly_ordered')->default(true);
            //todo for standalone auth  guard member
            // $table->string('name');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('password');
            // $table->rememberToken();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
