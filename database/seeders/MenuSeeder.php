<?php

    namespace Database\Seeders;

    use App\Models\DailyPackaging;
    use App\Models\MealNutrition;
    use App\Models\MealTranslation;
    use App\Models\MealVariant;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Throwable;


    /**
     * Seeder for all standard meal plans.
     * Relies on: database/seeders/data/all_plans_data.json
     */
    class MenuSeeder extends Seeder
    {
        /**
         * The path to the JSON file, relative to the database/seeders directory.
         *
         * @var string
         */
        private string $jsonFile = 'data/standred_weeks.json';

        /**
         * Converts a week key like "week1" to its integer index 1.
         *
         * @param string $weekKey
         * @return integer
         */
        private function weekIndex(string $weekKey): int
        {
            if (preg_match('/(\d+)/', $weekKey, $m)) {
                return (int) $m[1];
            }
            return 0;
        }

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run(): void
        {
            $out = $this->command->getOutput();
            $out->writeln("<info>Starting the All Plans Seeder...</info>");

            // Load the JSON file from the specified path
            $jsonPath = database_path('seeders/' . $this->jsonFile);
            if (!File::exists($jsonPath)) {
                $out->writeln("<error>JSON file not found at: {$jsonPath}</error>");
                return;
            }
            $out->writeln("-> Loading data from: {$jsonPath}");

            $data = json_decode(File::get($jsonPath), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $out->writeln('<error>Invalid JSON format. Please check the file for syntax errors.</error>');
                return;
            }

            // Use a database transaction to ensure atomicity
            DB::beginTransaction();

            try {
                $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack1', 'snack2'];

                foreach ($data as $calorieLevel => $weeks) {
                    $out->writeln("-------------------------------------------------");
                    $out->writeln("<comment>Processing Calorie Level: {$calorieLevel}</comment>");

                    foreach ($weeks as $weekKey => $days) {
                        $week = $this->weekIndex($weekKey);
                        if ($week === 0) {
                            $out->writeln("<warning>  - Skipping invalid week key: '{$weekKey}'</warning>");
                            continue;
                        }

                        foreach ($days as $day => $content) {
                            if (empty($content)) {
                                $out->writeln("<warning>  - Skipping empty day: Week {$week}, Day {$day}</warning>");
                                continue;
                            }
                            $out->writeln("  <info>-> Processing Week: {$week}, Day: {$day}</info>");

                            // Create or update packaging cost
                            if (isset($content['packaging']['cost'])) {
                                DailyPackaging::updateOrCreate(
                                    ['calorie_level' => (int) $calorieLevel, 'week' => $week, 'day' => $day],
                                    ['cost' => $content['packaging']['cost']]
                                );
                                $out->writeln("    - Packaging cost set to: {$content['packaging']['cost']}");
                            }

                            foreach ($mealTypes as $type) {
                                if (!isset($content[$type]['en']) || !is_array($content[$type]['en'])) {
                                    continue;
                                }
                                $out->writeln("    - Processing Meal Type: <options=bold>{$type}</>");

                                foreach ($content[$type]['en'] as $index => $enVariant) {
                                    $arVariant = $content[$type]['ar'][$index] ?? null;

                                    if (empty($enVariant['title']) || $arVariant === null) {
                                        $out->writeln("<warning>      - Skipping variant #{$index} due to missing English title or Arabic variant.</warning>");
                                        continue;
                                    }

                                    $out->writeln("      - Seeding Variant: '{$enVariant['title']}'");

                                    // Create MealVariant
                                    $mealVariant = MealVariant::create([
                                        'calorie_level' => (int) $calorieLevel,
                                        'week' => $week,
                                        'day' => $day,
                                        'meal_type' => $type,
                                        'option_no' => $index + 1,
                                        'price' => $enVariant['price'] ?? 0,
                                        'image' => $enVariant['image'] ?? null,
                                    ]);

                                    // Create MealTranslations
                                    MealTranslation::insert([
                                        ['meal_variant_id' => $mealVariant->id, 'locale' => 'en', 'title' => $enVariant['title'], 'description' => $enVariant['description']],
                                        ['meal_variant_id' => $mealVariant->id, 'locale' => 'ar', 'title' => $arVariant['title'], 'description' => $arVariant['description']],
                                    ]);

                                    // Create MealNutrition
                                    $nutrition = $enVariant['nutrition'];
                                    MealNutrition::create([
                                        'meal_variant_id' => $mealVariant->id,
                                        'protein' => $nutrition['Pro'] ?? null,
                                        'carb' => $nutrition['Carb'] ?? null,
                                        'fat' => $nutrition['Fats'] ?? null,
                                        'kcal' => $nutrition['kcal'] ?? null,
                                    ]);
                                }
                            }
                        }
                    }
                }

                DB::commit();
                $out->writeln("-------------------------------------------------");
                $out->writeln("\n<info>✅ All Plans Menu seeded successfully!</info>");

            } catch (Throwable $e) {
                DB::rollBack();
                $out->writeln("\n<error>An error occurred during seeding: {$e->getMessage()}</error>");
                $out->writeln("<error>Transaction rolled back. No data was saved.</error>");
            }
        }
    }
