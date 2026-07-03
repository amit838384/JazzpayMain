<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_school_student', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->string('student_name')->nullable();
            $table->string('grade', 100)->nullable();
            $table->string('gender', 30)->nullable();
            $table->string('dob', 30)->nullable();
            $table->string('wallet_balance')->nullable();
            $table->string('spend_limit')->nullable();
            $table->string('admission_no', 100)->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('transaction_type', 100)->nullable();
            $table->string('card_no')->nullable();
            $table->integer('card_status')->default(0);
            $table->string('verified')->nullable();
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->integer('wallet_payment_status')->default(0);
            $table->text('image')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_school_student');
    }
};
