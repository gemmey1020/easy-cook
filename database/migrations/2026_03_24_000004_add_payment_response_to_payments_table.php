<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('payments', 'payment_response')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->longText('payment_response')->nullable()->after('payment_date');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('payments', 'payment_response')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_response');
        });
    }
};
