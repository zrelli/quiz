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
            $table->unsignedTinyInteger('score')->nullable();
            $table->unsignedInteger('time_taken')->default(0);
            $table->unsignedInteger('successful_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->unsignedInteger('failed_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->unsignedInteger('total_attempts')->default(0); // has a value for  (in_time quiz) only
            $table->string('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
            $table->timestamps();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
