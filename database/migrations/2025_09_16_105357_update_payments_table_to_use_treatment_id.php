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
        Schema::table('payments', function (Blueprint $table) {
            // Add treatment_id column
            $table->foreignId('treatment_id')->nullable()->constrained('treatments')->onDelete('cascade');
            
            // Drop appointment_id column (make it nullable first if there's existing data)
            $table->foreignId('appointment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop treatment_id column
            $table->dropForeign(['treatment_id']);
            $table->dropColumn('treatment_id');
            
            // Restore appointment_id as required
            $table->foreignId('appointment_id')->nullable(false)->change();
        });
    }
};
