<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('coupon_v2_coupons')) {
            return;
        }

        Schema::create('coupon_v2_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('code_normalized')->unique();
            $table->enum('owner_type', ['system', 'partner']);
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->unsignedInteger('max_redemptions');
            $table->dateTime('expires_at');
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->boolean('auto_generated')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['owner_type', 'owner_user_id']);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('coupon_v2_coupons')) {
            Schema::dropIfExists('coupon_v2_coupons');
        }
    }
};
