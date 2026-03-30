<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('coupon_v2_usages')) {
            return;
        }

        Schema::create('coupon_v2_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_v2_coupon_id')->constrained('coupon_v2_coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete()->unique();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('payment_status', ['pending_payment', 'paid', 'failed'])->default('pending_payment');
            $table->decimal('total_before_discount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('total_after_discount', 10, 2);
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
            $table->index(['coupon_v2_coupon_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('coupon_v2_usages')) {
            Schema::dropIfExists('coupon_v2_usages');
        }
    }
};
