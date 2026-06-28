<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('default_test_size')->nullable()->default(40);
            $table->integer('default_test_time')->nullable()->default(45);
        });

        // Update subcategories table
        Schema::table('subcategories', function (Blueprint $table) {
            $table->integer('default_test_size')->nullable()->default(8);
            $table->integer('default_test_time')->nullable()->default(45);
        });

        // Update tests table
        Schema::table('tests', function (Blueprint $table) {
            $table->integer('duration_minutes')->default(45);
        });

        // Update access_codes table
        Schema::table('access_codes', function (Blueprint $table) {
            $table->string('type')->default('testing'); // 'testing' or 'resource'
            $table->text('resource_url')->nullable();
        });

        // Create test_sessions table
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_code_id')->constrained()->cascadeOnDelete();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('token')->unique();
            $table->json('questions_order'); // JSON array of question IDs in display order
            $table->json('answers')->nullable(); // JSON of user answers
            $table->integer('score')->nullable(); // Number of correct answers
            $table->integer('total_questions');
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        // Seed some defaults on categories and subcategories
        DB::table('categories')->where('name', 'databases')->update([
            'default_test_size' => 40,
            'default_test_time' => 45,
        ]);

        DB::table('subcategories')->update([
            'default_test_size' => 8,
            'default_test_time' => 45,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('test_sessions');

        Schema::table('access_codes', function (Blueprint $table) {
            $table->dropColumn(['type', 'resource_url']);
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('duration_minutes');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropColumn(['default_test_size', 'default_test_time']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['default_test_size', 'default_test_time']);
        });
    }
};
