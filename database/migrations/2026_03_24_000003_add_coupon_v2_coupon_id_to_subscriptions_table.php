<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('subscriptions', 'coupon_v2_coupon_id')) {
            return;
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('coupon_v2_coupon_id')
                ->nullable()
                ->after('coupon_id')
                ->constrained('coupon_v2_coupons')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('subscriptions', 'coupon_v2_coupon_id')) {
            return;
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_v2_coupon_id');
        });
    }
};
