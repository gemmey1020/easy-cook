<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('meal_variants')) {
            return;
        }

        $schema = DB::getDatabaseName();

        $legacyTargets = [
            'xxx_xxx_meal_translations' => [
                'target' => 'xxx_xxx_meal_variants',
                'canonical' => 'meal_translations_meal_variant_id_foreign',
                'legacy' => 'legacy_xxx_xxx_meal_translations_meal_variant_id_foreign',
            ],
            'xxx_xxx_meal_nutritions' => [
                'target' => 'xxx_xxx_meal_variants',
                'canonical' => 'meal_nutritions_meal_variant_id_foreign',
                'legacy' => 'legacy_xxx_xxx_meal_nutritions_meal_variant_id_foreign',
            ],
        ];

        foreach ($legacyTargets as $tableName => $config) {
            if (!Schema::hasTable($tableName) || !Schema::hasTable($config['target'])) {
                continue;
            }

            $constraintName = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $schema)
                ->where('TABLE_NAME', $tableName)
                ->where('COLUMN_NAME', 'meal_variant_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->value('CONSTRAINT_NAME');

            if (!$constraintName || $constraintName === $config['legacy']) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });

            Schema::table($tableName, function (Blueprint $table) use ($config) {
                $table->foreign('meal_variant_id', $config['legacy'])
                    ->references('id')
                    ->on($config['target'])
                    ->cascadeOnDelete();
            });
        }

        $cleanTargets = [
            'meal_translations' => 'meal_translations_meal_variant_id_foreign',
            'meal_nutritions' => 'meal_nutritions_meal_variant_id_foreign',
        ];

        foreach ($cleanTargets as $tableName => $constraintName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $referencedTable = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $schema)
                ->where('TABLE_NAME', $tableName)
                ->where('COLUMN_NAME', 'meal_variant_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->value('REFERENCED_TABLE_NAME');

            if ($referencedTable === 'meal_variants') {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $constraintName) {
                $table->foreign('meal_variant_id', $constraintName)
                    ->references('id')
                    ->on('meal_variants')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('xxx_xxx_meal_variants')) {
            return;
        }

        $schema = DB::getDatabaseName();
        $cleanTargets = [
            'meal_translations' => 'meal_translations_meal_variant_id_foreign',
            'meal_nutritions' => 'meal_nutritions_meal_variant_id_foreign',
        ];

        foreach ($cleanTargets as $tableName => $constraintName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            $exists = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $schema)
                ->where('TABLE_NAME', $tableName)
                ->where('CONSTRAINT_NAME', $constraintName)
                ->where('COLUMN_NAME', 'meal_variant_id')
                ->exists();

            if (!$exists) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        }

        $legacyTargets = [
            'xxx_xxx_meal_translations' => [
                'target' => 'xxx_xxx_meal_variants',
                'canonical' => 'meal_translations_meal_variant_id_foreign',
                'legacy' => 'legacy_xxx_xxx_meal_translations_meal_variant_id_foreign',
            ],
            'xxx_xxx_meal_nutritions' => [
                'target' => 'xxx_xxx_meal_variants',
                'canonical' => 'meal_nutritions_meal_variant_id_foreign',
                'legacy' => 'legacy_xxx_xxx_meal_nutritions_meal_variant_id_foreign',
            ],
        ];

        foreach ($legacyTargets as $tableName => $config) {
            if (!Schema::hasTable($tableName) || !Schema::hasTable($config['target'])) {
                continue;
            }

            $exists = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', $schema)
                ->where('TABLE_NAME', $tableName)
                ->where('CONSTRAINT_NAME', $config['legacy'])
                ->where('COLUMN_NAME', 'meal_variant_id')
                ->exists();

            if (!$exists) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($config) {
                $table->dropForeign($config['legacy']);
            });

            Schema::table($tableName, function (Blueprint $table) use ($config) {
                $table->foreign('meal_variant_id', $config['canonical'])
                    ->references('id')
                    ->on($config['target'])
                    ->cascadeOnDelete();
            });
        }
    }
};
