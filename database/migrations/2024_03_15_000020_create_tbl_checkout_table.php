<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_checkout', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('dish_id');
            $table->string('date', 100)->nullable();
            $table->integer('qty')->nullable();
            $table->integer('dish_price')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->string('payment_type', 30)->nullable();
            $table->integer('payment_status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_checkout');
    }
};
