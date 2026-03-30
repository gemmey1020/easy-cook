<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MigrationNormalizationTest extends TestCase
{
    public function test_assessments_notes_column_exists_in_target_schema(): void
    {
        $database = DB::getDatabaseName();

        $exists = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'assessments')
            ->where('COLUMN_NAME', 'notes')
            ->exists();

        $this->assertTrue($exists, 'The target schema must include assessments.notes.');
    }

    public function test_daily_selected_meals_meal_variant_fk_targets_meal_variants(): void
    {
        $database = DB::getDatabaseName();

        $referencedTable = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'daily_selected_meals')
            ->where('COLUMN_NAME', 'meal_variant_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('REFERENCED_TABLE_NAME');

        $this->assertSame(
            'meal_variants',
            $referencedTable,
            'daily_selected_meals.meal_variant_id must reference meal_variants in the normalized schema.'
        );
    }

    public function test_clean_meal_structure_tables_reference_meal_variants(): void
    {
        $database = DB::getDatabaseName();

        $relationships = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('TABLE_NAME', 'REFERENCED_TABLE_NAME')
            ->where('TABLE_SCHEMA', $database)
            ->whereIn('TABLE_NAME', ['meal_translations', 'meal_nutritions'])
            ->where('COLUMN_NAME', 'meal_variant_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->pluck('REFERENCED_TABLE_NAME', 'TABLE_NAME')
            ->all();

        $this->assertSame('meal_variants', $relationships['meal_translations'] ?? null);
        $this->assertSame('meal_variants', $relationships['meal_nutritions'] ?? null);
    }
}
