<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_preorders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('cafeteria_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('dish_id');
            $table->string('date', 100)->nullable();
            $table->integer('qty')->nullable();
            $table->integer('dish_price')->nullable();
            $table->integer('total_price')->nullable();
            $table->string('transaction_no', 100)->nullable();
            $table->integer('discount')->nullable();
            $table->string('payment_type', 20)->nullable();
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->integer('payment_status')->default(0)->comment('1-success 0-pending');
            $table->integer('pos_type')->nullable();
            $table->text('addons')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_preorders');
    }
};
