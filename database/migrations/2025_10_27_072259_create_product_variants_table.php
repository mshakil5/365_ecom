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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Relationships
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->cascadeOnDelete();

            // Variant Identification
            $table->string('variant_short_code')->nullable()->unique();
            $table->string('ean')->nullable()->unique();
            $table->string('short_code')->nullable();

            // Variant-Specific Pricing (Overrides product base price)
            $table->decimal('price_single', 10, 2)->nullable();
            $table->integer('qty_single')->nullable();
            $table->decimal('price_pack', 10, 2)->nullable();
            $table->integer('pack_qty')->nullable();
            $table->decimal('price_carton', 10, 2)->nullable();
            $table->integer('carton_qty')->nullable();
            $table->decimal('price_1k', 10, 2)->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('my_price', 10, 2)->nullable();

            // Variant-Specific Images
            $table->string('color_image')->nullable();
            $table->string('sm_color_image')->nullable();

            // Inventory & Status
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['product_id', 'color_id', 'size_id']);
            $table->index(['variant_short_code', 'is_active']);
            $table->index('ean');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
