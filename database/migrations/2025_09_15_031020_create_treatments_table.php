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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('treatment_type_id')->constrained();
            $table->text('description');
            $table->string('tooth_number')->nullable();
            $table->decimal('cost', 10, 2);
            $table->foreignId('performed_by')->constrained('dentists');
            $table->decimal('dentist_share', 5, 2)->default(0.40);
            $table->decimal('clinic_share', 5, 2)->default(0.60);
            $table->date('treatment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
