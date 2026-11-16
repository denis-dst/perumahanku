<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('house_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g. "Tipe 36/72"
            $table->string('slug');
            $table->integer('building_area')->nullable(); // m²
            $table->integer('land_area')->nullable(); // m²
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->integer('floors')->default(1);
            $table->decimal('price', 15, 0)->default(0);
            $table->text('description')->nullable();
            $table->json('specifications')->nullable();
            $table->string('floor_plan_image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['project_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_types');
    }
};
