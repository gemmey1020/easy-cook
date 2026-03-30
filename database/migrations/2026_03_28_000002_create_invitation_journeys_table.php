<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invitation_journeys')) {
            return;
        }

        Schema::create('invitation_journeys', function (Blueprint $table) {
            $table->id();
            $table->string('journey_token')->unique();
            $table->foreignId('invitation_code_id')->nullable()->constrained('invitation_codes')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('state', ['captured', 'registered', 'checkout_started', 'paid', 'invalid_code']);
            $table->enum('capture_channel', ['link', 'manual'])->default('manual');
            $table->string('raw_invitation_code')->nullable();
            $table->string('last_invalid_code')->nullable();
            $table->string('invalid_reason')->nullable();
            $table->timestamp('invalid_recorded_at')->nullable();
            $table->enum('referral_source', ['meta', 'google', 'direct_link', 'captain_gym', 'other'])->nullable();
            $table->string('referral_source_note', 255)->nullable();
            $table->foreignId('default_coupon_v2_coupon_id')->nullable()->constrained('coupon_v2_coupons')->nullOnDelete();
            $table->string('active_coupon_version', 10)->nullable();
            $table->unsignedBigInteger('active_coupon_id')->nullable();
            $table->string('active_coupon_code')->nullable();
            $table->string('override_coupon_version', 10)->nullable();
            $table->unsignedBigInteger('override_coupon_id')->nullable();
            $table->string('override_coupon_code')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('checkout_started_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->index(['state', 'user_id']);
            $table->index(['invitation_code_id', 'state']);
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('invitation_journeys')) {
            Schema::dropIfExists('invitation_journeys');
        }
    }
};
