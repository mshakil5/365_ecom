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
        Schema::table('order_customisations', function (Blueprint $table) {
            $table->integer('z_index')->nullable()->after('position');
            $table->string('layer_id')->nullable()->after('z_index');
            $table->json('data')->nullable()->after('layer_id');

            $table->dropColumn(['text_content','font_family','font_size','image_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_customisations', function (Blueprint $table) {
          $table->text('text_content')->nullable();
          $table->string('font_family')->nullable();
          $table->string('font_size')->nullable();
          $table->longText('image_url')->nullable();

          $table->dropColumn(['layer_id','z_index','data']);
        });
    }
};
