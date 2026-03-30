<?php

namespace App\Http\Controllers;

class SeoPageController extends Controller
{
    /**
     * SEO page content configuration.
     * Each service type has its own content structure with area-specific data.
     */
    private function getServiceConfig(): array
    {
        return [
            'meal-plans' => [
                'service_en' => 'Meal Plans',
                'service_ar' => 'خطط الوجبات',
                'meta_prefix' => 'Healthy Meal Plans',
                'icon' => 'fa-calendar-alt',
                'cta_link' => '/plans',
                'cta_text' => 'Browse Our Meal Plans',
                'areas' => [
                    'maadi' => [
                        'area_en' => 'Maadi',
                        'area_ar' => 'المعادي',
                        'title' => 'Healthy Meal Plans in Maadi | EasyCook',
                        'meta_title' => 'Customized Meal Plan & Dietary Planning Services | Maadi',
                        'meta_description' => 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness in Maadi.',
                        'h1' => 'Healthy Meal Plans Delivered in Maadi',
                        'intro' => 'Looking for a convenient way to eat healthy in Maadi? EasyCook provides customized weekly and monthly meal plans delivered fresh to your doorstep. Our plans are crafted by nutrition experts and prepared by professional chefs, using the finest locally sourced ingredients.',
                        'why_title' => 'Why Choose EasyCook Meal Plans in Maadi?',
                        'why_points' => [
                            'Personalized meal plans tailored to your dietary goals and preferences',
                            'Fresh ingredients prepared daily and delivered across all Maadi districts',
                            'Flexible subscription options — weekly, bi-weekly, or monthly plans',
                            'Calorie-counted meals designed for weight loss, maintenance, or muscle gain',
                        ],
                        'what_title' => 'What\'s Included in Our Maadi Meal Plans?',
                        'what_points' => [
                            'Balanced breakfast, lunch, dinner, and snack options',
                            'Variety of cuisines — Egyptian, Mediterranean, and international dishes',
                            'Special diet accommodations — keto, low-carb, high-protein, and vegetarian',
                            'Rotating weekly menus so you never get bored',
                        ],
                        'delivery_title' => 'Fast & Reliable Delivery in Maadi',
                        'delivery_points' => [
                            'Same-day delivery within all Maadi areas',
                            'Hygienic and eco-friendly packaging',
                            'On-time delivery guaranteed — morning or evening slots',
                        ],
                        'closing' => 'Whether you\'re a busy professional, a fitness enthusiast, or a parent looking for nutritious family meals — EasyCook meal plans in Maadi have you covered. Start your healthy eating journey today!',
                    ],
                    'nasr-city' => [
                        'area_en' => 'Nasr City',
                        'area_ar' => 'مدينة نصر',
                        'title' => 'Healthy Meal Plans in Nasr City | EasyCook',
                        'meta_title' => 'Customized Meal Plan & Dietary Planning Services | Nasr City',
                        'meta_description' => 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness in Nasr City.',
                        'h1' => 'Healthy Meal Plans Delivered in Nasr City',
                        'intro' => 'Tired of unhealthy fast food in Nasr City? EasyCook offers expertly designed meal plans that fit your lifestyle and nutritional needs. Enjoy restaurant-quality healthy meals delivered right to your home or office across Nasr City.',
                        'why_title' => 'Why Choose EasyCook Meal Plans in Nasr City?',
                        'why_points' => [
                            'Nutritionist-approved meal plans customized to your health goals',
                            'Convenient delivery covering all Nasr City neighborhoods',
                            'Budget-friendly plans without compromising on quality or taste',
                            'Easy online ordering and flexible subscription management',
                        ],
                        'what_title' => 'Our Nasr City Meal Plan Options',
                        'what_points' => [
                            'Complete daily meals — breakfast, lunch, dinner, and healthy snacks',
                            'Diet-specific plans: keto, paleo, low-carb, high-protein, and balanced',
                            'Family-sized meal plans available for households',
                            'Fresh weekly menus with seasonal ingredients',
                        ],
                        'delivery_title' => 'Reliable Delivery Across Nasr City',
                        'delivery_points' => [
                            'Timely delivery to all Nasr City zones and surrounding areas',
                            'Temperature-controlled packaging to keep meals fresh',
                            'Flexible delivery schedules that fit your routine',
                        ],
                        'closing' => 'Join thousands of satisfied customers in Nasr City who trust EasyCook for their daily nutrition. Subscribe today and transform the way you eat!',
                    ],
                    'new-cairo' => [
                        'area_en' => 'New Cairo',
                        'area_ar' => 'القاهرة الجديدة',
                        'title' => 'Healthy Meal Plans in New Cairo | EasyCook',
                        'meta_title' => 'Customized Meal Plan & Dietary Planning Services | New Cairo',
                        'meta_description' => 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness in New Cairo.',
                        'h1' => 'Healthy Meal Plans Delivered in New Cairo',
                        'intro' => 'Living in New Cairo and want to maintain a healthy diet without the hassle of cooking? EasyCook brings you chef-prepared, nutritionist-approved meal plans delivered fresh to your doorstep in New Cairo, including the Fifth Settlement, Rehab City, and Madinaty.',
                        'why_title' => 'Why EasyCook Is New Cairo\'s Top Meal Plan Provider',
                        'why_points' => [
                            'Tailored meal plans for every lifestyle — busy professionals, athletes, and families',
                            'Premium ingredients sourced from trusted local suppliers',
                            'Comprehensive coverage across all New Cairo compounds and neighborhoods',
                            'Subscription plans that save you time, money, and effort',
                        ],
                        'what_title' => 'Explore Our New Cairo Meal Plans',
                        'what_points' => [
                            'Full-day meal packages with breakfast, lunch, dinner, and snacks',
                            'Specialized plans for weight management, fitness, and medical diets',
                            'Kid-friendly options for growing children',
                            'New recipes added every week by our culinary team',
                        ],
                        'delivery_title' => 'Seamless Delivery in New Cairo',
                        'delivery_points' => [
                            'Daily delivery covering Fifth Settlement, Rehab, Madinaty, and more',
                            'Insulated packaging to preserve freshness and flavor',
                            'Choose your preferred delivery window',
                        ],
                        'closing' => 'Make healthy eating effortless in New Cairo. With EasyCook, every meal is a step toward a healthier you. Get started with your personalized meal plan now!',
                    ],
                    'heliopolis' => [
                        'area_en' => 'Heliopolis',
                        'area_ar' => 'مصر الجديدة',
                        'title' => 'Healthy Meal Plans in Heliopolis | EasyCook',
                        'meta_title' => 'Customized Meal Plan & Dietary Planning Services | Heliopolis',
                        'meta_description' => 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness in Heliopolis.',
                        'h1' => 'Healthy Meal Plans Delivered in Heliopolis',
                        'intro' => 'EasyCook is proud to serve Heliopolis with premium healthy meal plans designed to fuel your day. Whether you\'re in Korba, Alf Maskan, or Triumph — we deliver balanced, delicious meals prepared fresh every day.',
                        'why_title' => 'Why Heliopolis Residents Love EasyCook',
                        'why_points' => [
                            'Locally loved brand with a track record of quality and reliability',
                            'Plans designed by certified nutritionists to match your dietary goals',
                            'Wide delivery coverage across all Heliopolis districts',
                            'Hassle-free subscription — pause, modify, or cancel anytime',
                        ],
                        'what_title' => 'What Our Heliopolis Meal Plans Include',
                        'what_points' => [
                            'Three main meals plus snacks — perfectly portioned and calorie-counted',
                            'Diverse menu options including Egyptian classics and international flavors',
                            'Options for vegetarian, vegan, gluten-free, and allergy-friendly diets',
                            'Seasonal specials and limited-edition chef creations',
                        ],
                        'delivery_title' => 'Dependable Delivery in Heliopolis',
                        'delivery_points' => [
                            'Fast delivery to all Heliopolis neighborhoods',
                            'Secure, leak-proof packaging',
                            'Morning and evening delivery slots available',
                        ],
                        'closing' => 'From fitness goals to family dinners — EasyCook has the perfect meal plan for every Heliopolis resident. Order now and taste the difference!',
                    ],
                    '6th-of-october' => [
                        'area_en' => '6th of October',
                        'area_ar' => '6 أكتوبر',
                        'title' => 'Healthy Meal Plans in 6th of October | EasyCook',
                        'meta_title' => 'Customized Meal Plan & Dietary Planning Services | Sheikh Zaied',
                        'meta_description' => 'Discover personalized meal plan options with professional dietary planning and nutrition planning support. Easy Cook delivers balanced healthy meals tailored to your fitness in Sheikh Zaied.',
                        'h1' => 'Healthy Meal Plans Delivered in 6th of October City',
                        'intro' => 'EasyCook now delivers healthy, chef-prepared meal plans to 6th of October City! No more long drives for good food — enjoy balanced, flavorful meals crafted to support your health goals, delivered right to your door.',
                        'why_title' => 'Why 6th of October Chooses EasyCook',
                        'why_points' => [
                            'Dedicated delivery fleet covering all 6th of October districts and compounds',
                            'Meal plans that combine taste with nutrition — no boring diet food',
                            'Affordable pricing with transparent subscription tiers',
                            'Trusted by hundreds of families and professionals in October City',
                        ],
                        'what_title' => 'Our 6th of October Meal Plan Features',
                        'what_points' => [
                            'Daily meal sets: breakfast, lunch, dinner, and two snacks',
                            'Custom calorie targets for weight loss, gain, or maintenance',
                            'Plans for individuals, couples, and families',
                            'Weekly menu updates with fresh, exciting dishes',
                        ],
                        'delivery_title' => 'Delivery You Can Count On in October City',
                        'delivery_points' => [
                            'Full coverage including Sheikh Zayed nearby areas',
                            'Quality-sealed containers for maximum freshness',
                            'Punctual delivery — we respect your schedule',
                        ],
                        'closing' => 'Healthy eating in 6th of October City has never been easier. Let EasyCook handle your meals so you can focus on what matters. Subscribe today!',
                    ],
                ],
            ],

            'prepared-meals' => [
                'service_en' => 'Prepared Meals',
                'service_ar' => 'وجبات جاهزة',
                'meta_prefix' => 'Fresh Prepared Meals',
                'icon' => 'fa-utensils',
                'cta_link' => '/plans',
                'cta_text' => 'View Our Menu',
                'areas' => [
                    'maadi' => [
                        'area_en' => 'Maadi',
                        'area_ar' => 'المعادي',
                        'title' => 'Prepared Meals Delivery in Maadi | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | Maadi',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in Maadi.',
                        'h1' => 'Fresh Prepared Meals Delivered in Maadi',
                        'intro' => 'Skip the cooking and enjoy chef-prepared healthy meals delivered to your home in Maadi. EasyCook\'s prepared meals are made fresh daily with premium ingredients — ready to eat or simply heat and serve.',
                        'why_title' => 'Why Order Prepared Meals from EasyCook in Maadi?',
                        'why_points' => [
                            'Ready-to-eat meals prepared fresh every morning by professional chefs',
                            'No preservatives, no artificial flavors — just real, wholesome food',
                            'Perfect for busy professionals, students, and families in Maadi',
                            'Consistent quality and taste you can rely on daily',
                        ],
                        'what_title' => 'Our Prepared Meal Selection in Maadi',
                        'what_points' => [
                            'Grilled proteins, fresh salads, and nourishing soups',
                            'Traditional Egyptian dishes made the healthy way',
                            'International cuisine options — Asian, Mediterranean, and more',
                            'Healthy desserts and protein-packed snacks',
                        ],
                        'delivery_title' => 'Quick Delivery to Your Door in Maadi',
                        'delivery_points' => [
                            'Fresh meals delivered daily across all Maadi districts',
                            'Microwave-safe, sealed containers for convenience',
                            'Order by the meal or subscribe for daily delivery',
                        ],
                        'closing' => 'Enjoy restaurant-quality healthy food without leaving your home in Maadi. Order your prepared meals from EasyCook today!',
                    ],
                    'nasr-city' => [
                        'area_en' => 'Nasr City',
                        'area_ar' => 'مدينة نصر',
                        'title' => 'Prepared Meals Delivery in Nasr City | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | Nasr City',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in Nasr City.',
                        'h1' => 'Fresh Prepared Meals Delivered in Nasr City',
                        'intro' => 'Craving healthy food that\'s ready when you are? EasyCook delivers freshly prepared meals throughout Nasr City. Our kitchen team crafts every dish with care, balancing nutrition and flavor in every bite.',
                        'why_title' => 'Why Nasr City Loves EasyCook Prepared Meals',
                        'why_points' => [
                            'Made-to-order meals using the freshest seasonal ingredients',
                            'Healthy alternatives to takeout — without sacrificing taste',
                            'Portions designed by nutritionists for balanced macronutrients',
                            'Affordable meal bundles for individuals and families',
                        ],
                        'what_title' => 'What\'s on the Menu in Nasr City?',
                        'what_points' => [
                            'Lean proteins, whole grains, and farm-fresh vegetables',
                            'Chef-crafted daily specials that keep things exciting',
                            'Low-calorie, high-protein, and keto-friendly options',
                            'Sides, soups, and salads to complement any main',
                        ],
                        'delivery_title' => 'Fast & Fresh Delivery in Nasr City',
                        'delivery_points' => [
                            'Covering all Nasr City zones including Makram Ebeid, Abbas El-Akkad, and Mustafa El-Nahas',
                            'Insulated delivery bags to maintain temperature',
                            'Multiple daily delivery windows',
                        ],
                        'closing' => 'Say goodbye to unhealthy fast food in Nasr City. Choose EasyCook prepared meals for a delicious, nutritious alternative delivered to your door.',
                    ],
                    'new-cairo' => [
                        'area_en' => 'New Cairo',
                        'area_ar' => 'القاهرة الجديدة',
                        'title' => 'Prepared Meals Delivery in New Cairo | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | New City',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in New Cairo.',
                        'h1' => 'Fresh Prepared Meals Delivered in New Cairo',
                        'intro' => 'EasyCook serves New Cairo with daily fresh prepared meals that make healthy eating effortless. From Fifth Settlement to Rehab City and beyond — enjoy chef-quality dishes without spending time in the kitchen.',
                        'why_title' => 'Benefits of EasyCook Prepared Meals in New Cairo',
                        'why_points' => [
                            'Zero prep time — meals arrive ready to eat or heat',
                            'Diverse daily menu rotated by our culinary team',
                            'Clean eating made accessible and affordable',
                            'Trusted by New Cairo\'s fitness community and busy families',
                        ],
                        'what_title' => 'Prepared Meals Available in New Cairo',
                        'what_points' => [
                            'Grilled chicken, salmon, steak, and plant-based proteins',
                            'Signature rice bowls, wraps, and power salads',
                            'Light and filling soup options',
                            'Guilt-free treats and energy bites',
                        ],
                        'delivery_title' => 'Reliable New Cairo Delivery',
                        'delivery_points' => [
                            'Daily routes covering Fifth Settlement, Rehab, Madinaty, and compounds',
                            'Freshness-sealed packaging for every order',
                            'Convenient subscription or one-time order options',
                        ],
                        'closing' => 'Fuel your day the right way with EasyCook prepared meals in New Cairo. Fresh, healthy, and ready when you are — order now!',
                    ],
                    'sheikh-zayed' => [
                        'area_en' => 'Sheikh Zayed',
                        'area_ar' => 'الشيخ زايد',
                        'title' => 'Prepared Meals Delivery in Sheikh Zayed | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | Sheikh Zayed',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in Sheikh Zayed.',
                        'h1' => 'Fresh Prepared Meals Delivered in Sheikh Zayed',
                        'intro' => 'Healthy eating in Sheikh Zayed just got easier! EasyCook delivers chef-prepared, nutrition-balanced meals to your doorstep in Sheikh Zayed. Every meal is crafted with fresh ingredients and designed to keep you energized throughout the day.',
                        'why_title' => 'Why Sheikh Zayed Trusts EasyCook',
                        'why_points' => [
                            'Premium quality meals at competitive prices',
                            'Comprehensive delivery coverage across all Sheikh Zayed areas',
                            'Meals prepared in a certified, hygienic commercial kitchen',
                            'Dedicated customer support for seamless ordering',
                        ],
                        'what_title' => 'Our Sheikh Zayed Prepared Meals Menu',
                        'what_points' => [
                            'Lean meats, fresh seafood, and vegetarian proteins',
                            'Colorful grain bowls and Mediterranean platters',
                            'Comfort food classics made healthy',
                            'Fresh juices and smoothies to complement your meal',
                        ],
                        'delivery_title' => 'Sheikh Zayed Delivery Service',
                        'delivery_points' => [
                            'Serving all Sheikh Zayed compounds, malls, and office buildings',
                            'Eco-friendly packaging materials',
                            'Flexible delivery times to match your schedule',
                        ],
                        'closing' => 'Experience the convenience of healthy prepared meals delivered in Sheikh Zayed. Try EasyCook today and see why we\'re the area\'s favorite!',
                    ],
                    'mokattam' => [
                        'area_en' => 'Mokattam',
                        'area_ar' => 'المقطم',
                        'title' => 'Prepared Meals Delivery in Mokattam | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | Mokattam',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in Mokattam.',
                        'h1' => 'Fresh Prepared Meals Delivered in Mokattam',
                        'intro' => 'EasyCook now delivers healthy prepared meals across Mokattam! Enjoy perfectly balanced, chef-prepared dishes made with the freshest ingredients — delivered hot and ready to your home or office.',
                        'why_title' => 'Why Mokattam Residents Choose EasyCook',
                        'why_points' => [
                            'Fresh meals prepared daily — never frozen, never reheated',
                            'Nutritionally balanced options for every dietary need',
                            'Excellent value with transparent pricing',
                            'Easy ordering through our website with quick Mokattam delivery',
                        ],
                        'what_title' => 'Our Prepared Meal Options in Mokattam',
                        'what_points' => [
                            'Protein-rich mains with healthy carbs and fresh vegetables',
                            'Hearty soups and wholesome salad bowls',
                            'Kid-friendly meals that are both fun and nutritious',
                            'Light dinner options and healthy late-night snacks',
                        ],
                        'delivery_title' => 'Mokattam Delivery Coverage',
                        'delivery_points' => [
                            'Full coverage across Mokattam hills and surrounding areas',
                            'Carefully packed to arrive fresh and intact',
                            'Daily and weekly ordering options available',
                        ],
                        'closing' => 'Don\'t settle for less in Mokattam — enjoy EasyCook\'s healthy prepared meals and make every bite count. Start your order today!',
                    ],
                ],
            ],

            'nutrition-planning' => [
                'service_en' => 'Nutrition Planning',
                'service_ar' => 'تخطيط التغذية',
                'meta_prefix' => 'Personalized Nutrition Planning',
                'icon' => 'fa-heartbeat',
                'cta_link' => '/plans',
                'cta_text' => 'Start Your Nutrition Plan',
                'areas' => [
                    'maadi' => [
                        'area_en' => 'Maadi',
                        'area_ar' => 'المعادي',
                        'title' => 'Nutrition Planning in Maadi | EasyCook',
                        'meta_title' => 'Nutrition Planning & Healthy Meal Plan Experts - Maadi | Easy Cook',
                        'meta_description' => 'Optimize your lifestyle with professional nutrition planning and customized meal plan programs. Our healthy meals are crafted to support fitness and weight management in Maadi.',
                        'h1' => 'Personalized Nutrition Planning in Maadi',
                        'intro' => 'Achieve your health goals with EasyCook\'s personalized nutrition planning services in Maadi. Our certified nutritionists create tailored meal plans based on your body composition, lifestyle, and dietary preferences — and we deliver the meals right to you.',
                        'why_title' => 'Why Choose EasyCook Nutrition Planning in Maadi?',
                        'why_points' => [
                            'One-on-one consultation with certified nutritionists',
                            'Custom meal plans based on your health assessment and goals',
                            'Ongoing support and plan adjustments as you progress',
                            'Integrated meal delivery — your plan comes with the food',
                        ],
                        'what_title' => 'Our Nutrition Planning Services in Maadi',
                        'what_points' => [
                            'Personalized macronutrient and calorie targets',
                            'Weight loss, muscle building, and maintenance programs',
                            'Medical nutrition therapy for diabetes, cholesterol, and more',
                            'Meal plans for pregnant women, children, and seniors',
                        ],
                        'delivery_title' => 'How It Works in Maadi',
                        'delivery_points' => [
                            'Complete your online health assessment',
                            'Get matched with a nutritionist who builds your plan',
                            'Receive your freshly prepared meals daily in Maadi',
                        ],
                        'closing' => 'Your journey to better health starts with the right nutrition plan. EasyCook in Maadi combines expert guidance with delicious meals — sign up today!',
                    ],
                    'nasr-city' => [
                        'area_en' => 'Nasr City',
                        'area_ar' => 'مدينة نصر',
                        'title' => 'Nutrition Planning in Nasr City | EasyCook',
                        'meta_title' => 'Nutrition Planning & Healthy Meal Plan Experts - Nasr City| Easy Cook',
                        'meta_description' => 'Optimize your lifestyle with professional nutrition planning and customized meal plan programs. Our healthy meals are crafted to support fitness and weight management in Nasr City.',
                        'h1' => 'Personalized Nutrition Planning in Nasr City',
                        'intro' => 'Take control of your health with EasyCook\'s nutrition planning in Nasr City. We pair expert dietary guidance with fresh meal delivery, making it easier than ever to eat right and feel great.',
                        'why_title' => 'Expert Nutrition Planning for Nasr City',
                        'why_points' => [
                            'Science-based nutrition plans designed by qualified dietitians',
                            'Plans adapted to Egyptian food culture and preferences',
                            'Regular progress tracking and plan optimization',
                            'Seamless integration with EasyCook\'s meal delivery service',
                        ],
                        'what_title' => 'Nutrition Programs Available in Nasr City',
                        'what_points' => [
                            'Body composition analysis and personalized calorie planning',
                            'Sport-specific nutrition for athletes and gym-goers',
                            'Therapeutic diets for managing chronic conditions',
                            'Family nutrition programs for all ages',
                        ],
                        'delivery_title' => 'Getting Started in Nasr City',
                        'delivery_points' => [
                            'Sign up and complete your dietary profile online',
                            'Receive your customized nutrition plan within 24 hours',
                            'Enjoy daily delivery of your planned meals across Nasr City',
                        ],
                        'closing' => 'Stop guessing and start eating right. EasyCook\'s nutrition planning in Nasr City gives you the roadmap and the meals to reach your goals. Join now!',
                    ],
                    'new-cairo' => [
                        'area_en' => 'New Cairo',
                        'area_ar' => 'القاهرة الجديدة',
                        'title' => 'Nutrition Planning in New Cairo | EasyCook',
                        'meta_title' => 'Nutrition Planning & Healthy Meal Plan Experts - New Cairo| Easy Cook',
                        'meta_description' => 'Optimize your lifestyle with professional nutrition planning and customized meal plan programs. Our healthy meals are crafted to support fitness and weight management in New Cairo.',
                        'h1' => 'Personalized Nutrition Planning in New Cairo',
                        'intro' => 'EasyCook offers comprehensive nutrition planning services in New Cairo, combining professional dietary advice with convenient meal delivery. Whether you\'re targeting weight loss, athletic performance, or overall wellness — we\'ve got the plan for you.',
                        'why_title' => 'Why New Cairo Trusts EasyCook for Nutrition Planning',
                        'why_points' => [
                            'Holistic approach: nutrition advice plus perfectly prepared meals',
                            'Plans customized for your metabolism, activity level, and goals',
                            'Flexible options for individuals, couples, and families',
                            'Ongoing nutritionist support via chat and phone',
                        ],
                        'what_title' => 'Nutrition Planning Options in New Cairo',
                        'what_points' => [
                            'Comprehensive metabolic assessments',
                            'Customized macro-balanced meal plans',
                            'Pre and post-workout nutrition strategies',
                            'Detox and cleanse programs',
                        ],
                        'delivery_title' => 'Your Path to Health in New Cairo',
                        'delivery_points' => [
                            'Complete a quick online assessment',
                            'Get a detailed nutrition plan from our experts',
                            'Receive your meals fresh daily in New Cairo — no cooking required',
                        ],
                        'closing' => 'New Cairo deserves world-class nutrition services. With EasyCook, you get both the plan and the meals. Start your transformation today!',
                    ],
                    'heliopolis' => [
                        'area_en' => 'Heliopolis',
                        'area_ar' => 'مصر الجديدة',
                        'title' => 'Nutrition Planning in Heliopolis | EasyCook',
                        'meta_title' => 'Nutrition Planning & Healthy Meal Plan Experts - Helioplis | Easy Cook',
                        'meta_description' => 'Optimize your lifestyle with professional nutrition planning and customized meal plan programs. Our healthy meals are crafted to support fitness and weight management in Heliopolis.',
                        'h1' => 'Personalized Nutrition Planning in Heliopolis',
                        'intro' => 'EasyCook brings professional nutrition planning to Heliopolis. Our team of certified dietitians will design a meal plan around your health needs, and our chefs will bring it to life with fresh, delicious daily meals delivered to your door.',
                        'why_title' => 'Why Choose EasyCook for Nutrition Planning in Heliopolis?',
                        'why_points' => [
                            'Certified nutritionists with years of clinical experience',
                            'Plans that work with your schedule, budget, and taste preferences',
                            'Full meal delivery service included — eat exactly what your plan prescribes',
                            'Regular check-ins and plan adjustments for optimal results',
                        ],
                        'what_title' => 'Available Nutrition Programs in Heliopolis',
                        'what_points' => [
                            'Weight management programs with proven results',
                            'High-performance plans for athletes and active individuals',
                            'Nutrition plans for special medical conditions',
                            'Child and adolescent nutrition programs',
                        ],
                        'delivery_title' => 'Start Your Journey in Heliopolis',
                        'delivery_points' => [
                            'Fill out your health profile and goals online',
                            'Receive a personalized plan within one business day',
                            'Get your meals delivered fresh across Heliopolis daily',
                        ],
                        'closing' => 'Heliopolis, it\'s time to eat smarter. Let EasyCook\'s nutrition experts guide your diet while our chefs take care of the cooking. Sign up now!',
                    ],
                ],
            ],

            'meal-prep' => [
                'service_en' => 'Meal Prep',
                'service_ar' => 'تحضير الوجبات',
                'meta_prefix' => 'Healthy Meal Prep',
                'icon' => 'fa-box',
                'cta_link' => '/plans',
                'cta_text' => 'Order Your Meal Prep',
                'areas' => [
                    'new-cairo' => [
                        'area_en' => 'New Cairo',
                        'area_ar' => 'القاهرة الجديدة',
                        'title' => 'Meal Prep Delivery in New Cairo | EasyCook',
                        'meta_title' => 'Meal Prep & Food Prepared Meals for Busy Lifestyles | New Cairo | Easy Cook',
                        'meta_description' => 'Save time with our professional meal prep services. Easy Cook offers ready food prepared meals, structured meal plan options, and expert dietary planning in New Cairo.',
                        'h1' => 'Healthy Meal Prep Delivered in New Cairo',
                        'intro' => 'Simplify your week with EasyCook\'s meal prep service in New Cairo! We prepare, portion, and deliver fresh meals that are ready to store and enjoy throughout the week. Perfect for anyone who wants to eat healthy without spending hours in the kitchen.',
                        'why_title' => 'Why EasyCook Meal Prep in New Cairo?',
                        'why_points' => [
                            'Batch-prepared meals portioned for the week — just grab and go',
                            'Perfectly balanced macros for your fitness and health goals',
                            'Saves you time on grocery shopping, cooking, and cleaning',
                            'Consistent quality from our professional kitchen to your fridge',
                        ],
                        'what_title' => 'Our Meal Prep Options in New Cairo',
                        'what_points' => [
                            '3-day and 5-day meal prep packages',
                            'Protein-packed bowls, lean wraps, and fresh salads',
                            'Customizable based on dietary restrictions and preferences',
                            'Fridge-ready containers that reheat in minutes',
                        ],
                        'delivery_title' => 'Meal Prep Delivery in New Cairo',
                        'delivery_points' => [
                            'Weekly delivery to Fifth Settlement, Rehab, Madinaty, and all New Cairo areas',
                            'Refrigeration-safe packaging for multi-day freshness',
                            'Choose your delivery day and time slot',
                        ],
                        'closing' => 'Meal prep in New Cairo has never been this easy. Let EasyCook do the heavy lifting so you can enjoy healthy, ready-made meals all week long. Order your first prep now!',
                    ],
                ],
            ],
        ];
    }

    /**
     * Display the SEO landing page for the given service and area.
     */
    public function show(string $service, string $area)
    {
        $config = $this->getServiceConfig();

        if (!isset($config[$service]) || !isset($config[$service]['areas'][$area])) {
            abort(404);
        }

        $serviceData = $config[$service];
        $areaData = $serviceData['areas'][$area];

        return view('pages.seo-landing', [
            'service' => $serviceData,
            'area' => $areaData,
        ]);
    }
}
