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
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('subcategory');
            $table->foreignId('primary_subcategory_id')->constrained('subcategories');
            $table->foreignId('secondary_subcategory_id')->nullable()->constrained('subcategories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['primary_subcategory_id']);
            $table->dropForeign(['secondary_subcategory_id']);
            $table->dropColumn(['primary_subcategory_id', 'secondary_subcategory_id']);
            $table->string('subcategory')->nullable();
        });
    }
};
