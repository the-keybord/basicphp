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
            $table->unsignedBigInteger('sibling_group_id')->nullable()->after('correct_answer_string');
            $table->index('sibling_group_id');
        });

        Schema::create('ignored_sibling_pairs', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id_1');
            $table->unsignedBigInteger('question_id_2');
            
            $table->primary(['question_id_1', 'question_id_2']);
            
            $table->foreign('question_id_1')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('question_id_2')->references('id')->on('questions')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ignored_sibling_pairs');
        
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['sibling_group_id']);
            $table->dropColumn('sibling_group_id');
        });
    }
};
