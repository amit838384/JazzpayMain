<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_no', 50);
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('cafeteria_id');
            $table->string('date', 50);
            $table->decimal('total_amount', 10, 2);
            $table->integer('discount')->default(0);
            $table->decimal('after_discount', 10, 2)->default(0.00);
            $table->decimal('wallet_used', 10, 2)->default(0.00);
            $table->decimal('payable', 10, 2);
            $table->decimal('grand_total', 10, 2)->nullable();
            $table->string('payment_type', 50)->default('pos');
            $table->boolean('payment_status')->default(0);
            $table->string('creditcard', 100)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_orders');
    }
};
