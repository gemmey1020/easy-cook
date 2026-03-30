<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assessments') || Schema::hasColumn('assessments', 'notes')) {
            return;
        }

        Schema::table('assessments', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('assessments') || !Schema::hasColumn('assessments', 'notes')) {
            return;
        }

        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
