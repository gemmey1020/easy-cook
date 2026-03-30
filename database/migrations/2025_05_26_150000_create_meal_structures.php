<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ------------------------------------------------------------------
        //  STEP 0) Align subscriptions.id to BIGINT UNSIGNED if possible
        // ------------------------------------------------------------------
        if (Schema::hasTable('subscriptions')) {
            $col = Schema::getColumnType('subscriptions', 'id');
            if ($col !== 'bigint') {
                // Note: change() fails if column is INT PRIMARY AUTO_INCREMENT in MySQL < 8; safe fallback: alter raw SQL
                Schema::table('subscriptions', function (Blueprint $table) {
                    $table->unsignedBigInteger('id', true)->change();
                });
            }
        }

        // ------------------------------------------------------------------
        //  1) meal_variants
        // ------------------------------------------------------------------
        Schema::create('meal_variants', function (Blueprint $table) {
            $table->id();
            $table->string('meal_id', 191)->index()->nullable();                    // BIGINT UNSIGNED AI
            $table->unsignedSmallInteger('calorie_level');            // 1400 / 1700 …
            $table->unsignedTinyInteger('week');                      // 1‑4
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']);
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2']);
            $table->unsignedTinyInteger('option_no');                 // 1‑3 (Option 1 = Standard)
            $table->decimal('price', 8, 2)->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
            $table->unique(['calorie_level', 'week', 'day', 'meal_type', 'option_no'], 'uniq_variant');
        });

        // ------------------------------------------------------------------
        //  2) meal_translations (title/description per locale)
        // ------------------------------------------------------------------
        Schema::create('meal_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_variant_id')->constrained('meal_variants')->cascadeOnDelete();
            $table->enum('locale', ['en', 'ar']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['meal_variant_id', 'locale']);
        });

        // ------------------------------------------------------------------
        //  3) meal_nutritions (one‑to‑one with variant)
        // ------------------------------------------------------------------
        Schema::create('meal_nutritions', function (Blueprint $table) {
            $table->unsignedBigInteger('meal_variant_id')->primary();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('carb', 6, 2)->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('kcal', 6, 2)->nullable();
            $table->foreign('meal_variant_id')->references('id')->on('meal_variants')->cascadeOnDelete();
            $table->timestamps();
        });

        // ------------------------------------------------------------------
        //  4) daily_packaging (fixed cost per day)
        // ------------------------------------------------------------------
        Schema::create('daily_packaging', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('calorie_level');
            $table->unsignedTinyInteger('week');
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday']);
            $table->decimal('cost', 8, 2);
            $table->timestamps();
            $table->unique(['calorie_level', 'week', 'day']);
        });

        // ------------------------------------------------------------------
        //  5) daily_selected_meals (chosen meals per subscription day)
        // ------------------------------------------------------------------
        Schema::create('daily_selected_meals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');            // match subscriptions.id (assumed BIGINT UNSIGNED)
            $table->date('day');
            $table->foreignId('meal_variant_id')->constrained('meal_variants');
            $table->unsignedTinyInteger('option_no');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2']);
            $table->timestamps();

            $table->unique(['subscription_id', 'day', 'meal_type']);
            // Add FK only if table & types align
            if (Schema::hasTable('subscriptions')) {
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_selected_meals');
        Schema::dropIfExists('daily_packaging');
        Schema::dropIfExists('meal_nutritions');
        Schema::dropIfExists('meal_translations');
        Schema::dropIfExists('meal_variants');
    }
};
