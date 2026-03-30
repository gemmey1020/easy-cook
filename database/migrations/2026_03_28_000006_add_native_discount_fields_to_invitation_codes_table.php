<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invitation_codes')) {
            return;
        }

        Schema::table('invitation_codes', function (Blueprint $table) {
            if (! Schema::hasColumn('invitation_codes', 'discount_type')) {
                $table->enum('discount_type', ['percent', 'fixed'])
                    ->nullable()
                    ->after('default_coupon_v2_coupon_id');
            }

            if (! Schema::hasColumn('invitation_codes', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)
                    ->nullable()
                    ->after('discount_type');
            }

            if (! Schema::hasColumn('invitation_codes', 'max_redemptions')) {
                $table->unsignedInteger('max_redemptions')
                    ->nullable()
                    ->after('discount_value');
            }
        });

        DB::statement(
            'UPDATE invitation_codes ic
             INNER JOIN coupon_v2_coupons c ON c.id = ic.default_coupon_v2_coupon_id
             SET ic.discount_type = COALESCE(ic.discount_type, c.discount_type),
                 ic.discount_value = COALESCE(ic.discount_value, c.discount_value),
                 ic.max_redemptions = COALESCE(ic.max_redemptions, c.max_redemptions)
             WHERE ic.default_coupon_v2_coupon_id IS NOT NULL'
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('invitation_codes')) {
            return;
        }

        Schema::table('invitation_codes', function (Blueprint $table) {
            foreach (['max_redemptions', 'discount_value', 'discount_type'] as $column) {
                if (Schema::hasColumn('invitation_codes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
