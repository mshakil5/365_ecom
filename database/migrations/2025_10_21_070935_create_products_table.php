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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Main Fields
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            $table->foreignId('api_log_id')->nullable()->constrained('api_logs')->onDelete('set null');

            // Product Identification
            $table->string('product_code')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();

            // Product Specifications (Common to ALL variants)
            $table->text('full_description')->nullable();
            $table->string('short_code')->nullable();
            $table->string('tariff_no')->nullable();
            $table->integer('wash_degrees')->nullable();
            $table->string('gender')->nullable();
            $table->integer('gsm')->nullable();
            $table->text('composition')->nullable();
            $table->text('specifications')->nullable();
            $table->string('packaging')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->decimal('gross_weight', 8, 2)->nullable();
            $table->decimal('net_weight', 8, 2)->nullable();
            $table->string('tax_code')->nullable();

            // Media (Product-level images)
            $table->string('feature_image')->nullable(); // Main image
            $table->string('small_image')->nullable(); // Thumbnail
            $table->text('video_link')->nullable();

            // Pricing (Base pricing - can be overridden by variants)
            $table->decimal('price', 10, 2)->nullable()->index();
            $table->integer('discount_percent')->nullable();
            
            // Meta Fields
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();

            // Status & Visibility
            $table->boolean('status')->default(true)->index();
            $table->boolean('show_in_frontend')->default(true)->index();
            $table->unsignedBigInteger('views')->default(0)->index();
            $table->unsignedTinyInteger('product_source')->default(1)->index()->comment('1 = Manual, 2 = API');

            // Audit
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->index(['product_code', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
