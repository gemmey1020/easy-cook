<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');

            $table->enum('subscription_type', ['weight_loss', 'fitness', 'weight_gain']);
            $table->string('calorie_range')->nullable(); // 1300-1500, 1600-1800, 1900-2200

            $table->integer('weeks_count'); // (1-4)
            $table->integer('days_per_week'); // (5 أو 6)
            $table->json('excluded_days')->nullable(); // الجمعة / السبت

            $table->decimal('price', 8, 2); // سعر الاشتراك
            $table->integer('total_meals')->nullable(); // إجمالي عدد الوجبات

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'active', 'canceled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
