<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invitation_codes')) {
            return;
        }

        Schema::create('invitation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('code_normalized')->unique();
            $table->enum('owner_type', ['system', 'partner']);
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('default_coupon_v2_coupon_id')->nullable()->constrained('coupon_v2_coupons')->nullOnDelete();
            $table->enum('discount_type', ['percent', 'fixed'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->unsignedInteger('max_redemptions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('auto_generated')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['owner_type', 'owner_user_id']);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('invitation_codes')) {
            Schema::dropIfExists('invitation_codes');
        }
    }
};
