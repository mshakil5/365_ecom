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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_customizable')->nullable()->after('show_in_frontend');
            $table->boolean('is_trending')->nullable()->after('is_customizable');
            $table->boolean('is_popular')->nullable()->after('is_trending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_customizable', 'is_trending', 'is_popular']);
        });
    }
};
