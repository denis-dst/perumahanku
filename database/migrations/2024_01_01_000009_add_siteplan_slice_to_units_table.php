<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->text('polygon_coords')->nullable()->after('position_y');
            $table->string('slice_image')->nullable()->after('polygon_coords');
            $table->decimal('width_pct', 8, 4)->nullable()->after('slice_image');
            $table->decimal('height_pct', 8, 4)->nullable()->after('width_pct');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['polygon_coords', 'slice_image', 'width_pct', 'height_pct']);
        });
    }
};
