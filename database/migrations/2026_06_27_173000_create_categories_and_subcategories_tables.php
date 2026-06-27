<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            
            $table->unique(['category_id', 'name']);
        });

        // Seed default databases category and subcategories
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'databases',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subcategories = [
            'Database design',
            'Data retrieval',
            'Database object management',
            'Data manipulation',
            'Troubleshooting'
        ];

        foreach ($subcategories as $sub) {
            DB::table('subcategories')->insert([
                'category_id' => $categoryId,
                'name' => $sub,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('categories');
    }
};
