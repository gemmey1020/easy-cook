<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {

            // Add new columns
            $table->string('gender')->nullable()->after('notes');
            $table->integer('age')->nullable()->after('gender');
            $table->string('goal')->nullable()->after('age');
            $table->date('birth_date')->nullable()->after('goal');
            $table->string('activity_level')->nullable()->after('birth_date');
            $table->decimal('calories_needed', 8, 2)->nullable()->after('activity_level');
            $table->decimal('protein', 8, 2)->nullable()->after('calories_needed');
            $table->decimal('carbohydrates', 8, 2)->nullable()->after('protein');
            $table->decimal('calories_intake', 8, 2)->nullable()->after('carbohydrates');
            // water_percentage

            // Make existing columns nullable
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('mobile')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->decimal('bmi', 5, 2)->nullable()->change();
            $table->decimal('water_percentage', 5, 2)->nullable()->change();
            $table->decimal('muscle_mass', 5, 2)->nullable()->change();
            $table->text('notes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {

            $table->dropColumn([
                'gender',
                'age',
                'goal',
                'birth_date',
                'activity_level',
                'calories_needed',
                'protein',
                'carbohydrates',
                'calories_intake',
            ]);

            $table->string('first_name')->change();
            $table->string('last_name')->change();
            $table->string('email')->change();
            $table->string('mobile')->change();
            $table->string('city')->change();
            $table->decimal('bmi', 5, 2)->change();
            $table->decimal('muscle_mass', 5, 2)->change();
            $table->text('notes')->change();
        });
    }
};
