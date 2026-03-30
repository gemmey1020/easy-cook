<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('daily_selected_meals') || !Schema::hasTable('meal_variants')) {
            return;
        }

        $schema = DB::getDatabaseName();
        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME', 'REFERENCED_TABLE_NAME')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', 'daily_selected_meals')
            ->where('COLUMN_NAME', 'meal_variant_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->first();

        if ($constraint && $constraint->REFERENCED_TABLE_NAME === 'meal_variants') {
            return;
        }

        Schema::table('daily_selected_meals', function (Blueprint $table) use ($constraint) {
            if ($constraint) {
                $table->dropForeign($constraint->CONSTRAINT_NAME);
            }

            $table->foreign('meal_variant_id', 'daily_selected_meals_meal_variant_id_foreign')
                ->references('id')
                ->on('meal_variants');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('daily_selected_meals')) {
            return;
        }

        $schema = DB::getDatabaseName();
        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME', 'REFERENCED_TABLE_NAME')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', 'daily_selected_meals')
            ->where('COLUMN_NAME', 'meal_variant_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->first();

        if (!$constraint || $constraint->REFERENCED_TABLE_NAME !== 'meal_variants') {
            return;
        }

        Schema::table('daily_selected_meals', function (Blueprint $table) {
            $table->dropForeign('daily_selected_meals_meal_variant_id_foreign');
        });

        if (Schema::hasTable('xxx_xxx_meal_variants')) {
            Schema::table('daily_selected_meals', function (Blueprint $table) {
                $table->foreign('meal_variant_id', 'daily_selected_meals_meal_variant_id_foreign')
                    ->references('id')
                    ->on('xxx_xxx_meal_variants');
            });
        } elseif (Schema::hasTable('meal_variants')) {
            Schema::table('daily_selected_meals', function (Blueprint $table) {
                $table->foreign('meal_variant_id', 'daily_selected_meals_meal_variant_id_foreign')
                    ->references('id')
                    ->on('meal_variants');
            });
        }
    }
};
