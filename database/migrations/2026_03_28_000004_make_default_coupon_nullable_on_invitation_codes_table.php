<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invitation_codes')) {
            return;
        }

        DB::statement('ALTER TABLE invitation_codes MODIFY default_coupon_v2_coupon_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('invitation_codes')) {
            return;
        }

        DB::statement('ALTER TABLE invitation_codes MODIFY default_coupon_v2_coupon_id BIGINT UNSIGNED NOT NULL');
    }
};
