<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('house_type_id')->constrained()->cascadeOnDelete();
            $table->string('block')->nullable(); // e.g. "A", "B"
            $table->string('number'); // no. kavling
            $table->integer('land_area')->nullable();
            $table->decimal('price', 15, 0)->default(0);
            $table->string('facing')->nullable(); // hadap: Utara, Selatan, etc.
            $table->enum('status', ['available', 'booked', 'sold'])->default('available');
            $table->text('pov_description')->nullable();
            $table->decimal('position_x', 8, 4)->nullable(); // posisi di siteplan (%)
            $table->decimal('position_y', 8, 4)->nullable(); // posisi di siteplan (%)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
