<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ربط العميل بالتقييم
            $table->string('first_name'); // الاسم الأول
            $table->string('last_name'); // الاسم الأخير
            $table->string('email'); // البريد الإلكتروني
            $table->string('mobile'); // رقم الهاتف
            $table->string('city'); // المدينة
            $table->decimal('weight', 5, 2); // الوزن
            $table->decimal('height', 5, 2); // الطول
            $table->decimal('bmi', 5, 2); // مؤشر كتلة الجسم
            $table->decimal('fat_percentage', 5, 2); // نسبة الدهون
            $table->decimal('muscle_mass', 5, 2); // كتلة العضلات
            $table->decimal('water_percentage', 5, 2); // نسبة الماء
            $table->enum('consultation_type', ['in_person', 'phone'])->nullable(); // نوع الاستشارة
            $table->string('status')->default('pending'); // الحالة
            $table->text('notes')->nullable(); // الملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
