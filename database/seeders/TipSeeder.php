<?php

namespace Database\Seeders;

use App\Models\Tip;
use Illuminate\Database\Seeder;

class TipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Tip::insert([
            [
                'title' => '10 Steps to a Healthier Lifestyle',
                'content' => "1. Hydrate first thing in the morning.\n2. Create balanced meals with lean proteins, healthy fats, and whole grains.\n3. Add movement to your daily routine, even if it’s just a 10-minute walk.\n4. Practice mindful eating—savor every bite without distractions.\n5. Plan and prep your meals to avoid last-minute unhealthy choices.\n6. Snack smart with options like nuts, fruits, and yogurt.\n7. Prioritize 7–8 hours of quality sleep for better energy and mood.\n8. Focus on consistency over perfection; every small step counts.\n9. Monitor your habits to stay accountable and track progress.\n10. Keep it fun—explore new recipes, activities, and celebrate wins.",
            ],
            [
                'title' => 'The Power of Morning Routines',
                'content' => "1. Wake Up at the Same Time Daily: Consistency regulates your body clock and improves sleep quality.\n2. Hydrate Immediately: Start with a glass of water to rehydrate and energize.\n3. Move Your Body: Whether it’s yoga, a brisk walk, or a 5-minute stretch, morning movement wakes up your muscles and mind.\n4. Eat a Nutritious Breakfast: Fuel your day with whole foods, like eggs, oatmeal, or a smoothie packed with fruits and greens.\n5. Set Intentions: Spend a few minutes journaling or visualizing your goals for the day.",
            ],
            [
                'title' => 'Easy Ways to Stay Active Without the Gym',
                'content' => "1. Take Walks: A simple 30-minute walk in your neighborhood is great for your heart and mind.\n2. Try a Dance Workout: Put on your favorite playlist and dance—it’s fun and burns calories!\n3. Use Everyday Moments: Take the stairs instead of the elevator, or do squats while brushing your teeth.\n4. Join a Sports League: Playing a team sport like soccer or tennis makes exercise social and exciting.\n5. Explore the Outdoors: Go hiking, biking, or kayaking to combine exercise with fresh air and nature.",
            ],
            [
                'title' => '3 Reasons Why Easy Cook Will Transform Your Life!',
                'content' => "1. Tailored Meals Just for You: At Easy Cook, we don’t just serve meals; we create experiences. Every meal is customized to fit your unique dietary goals and health needs.\n2. Nutritional Precision at Its Best: Every ingredient in your Easy Cook meal is carefully selected and measured to perfection.\n3. Timely Delivery, Always: We know how precious your time is. That’s why Easy Cook prides itself on punctuality.",
            ],
        ]);
    }
}
