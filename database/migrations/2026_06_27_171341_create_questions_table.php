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
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->string('subcategory')->nullable();
        $table->string('question_type'); // e.g., 'multiselect', 'truefalse'
        $table->longText('xml_content'); // The raw XML payload
        $table->string('correct_answer_string')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
