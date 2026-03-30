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
        //        Schema::create('subscription_meals', function (Blueprint $table) {
        //            $table->id();
        //            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
        //            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
        //
        //            $table->date('meal_date'); // تاريخ الوجبة لهذا الاشتراك
        //            $table->timestamps();
        //        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_meals');
    }
};
