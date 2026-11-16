<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpr_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('logo')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0); // %
            $table->integer('max_tenor')->default(20); // tahun
            $table->decimal('min_dp_percent', 5, 2)->default(10); // %
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpr_banks');
    }
};
