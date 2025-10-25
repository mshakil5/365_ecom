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
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('code')->nullable()->index();
            $table->string('feature_image')->nullable();
            $table->Text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->decimal('price', 8, 2)->nullable()->index();
            $table->integer('discount_percent')->nullable(); 
          
            // Foreign Keys
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->cascadeOnDelete();
            $table->foreignId('product_model_id')->nullable()->constrained('product_models')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->cascadeOnDelete();
            $table->foreignId('type_id')->nullable()->constrained('types')->cascadeOnDelete();
            
            // Status/Boolean Flags
            $table->boolean('is_featured')->default(0)->index();
            $table->boolean('is_recent')->default(0)->index();  
            $table->boolean('is_popular')->default(0)->index(); 
            $table->boolean('is_trending')->default(0)->index();
            $table->boolean('is_new_arrival')->default(0)->index();
            $table->boolean('is_top_rated')->default(0)->index();
            
            // Meta/Image Fields
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            
            // Status and Views
            $table->boolean('status')->default(1)->index();
            $table->unsignedBigInteger('views')->default(0)->index();
            $table->unsignedTinyInteger('product_source')->default(1)->comment('1: Normal/Manual, 2: API Made')->index();

            // Api Fields
            $table->foreignId('api_log_id')->nullable()->constrained('api_logs')->onDelete('set null');
            $table->string('company')->nullable()->index(); 
            $table->string('category_api')->nullable(); 
            $table->string('product_code_api')->nullable()->index();
            $table->string('product_name_api')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('short_code')->nullable();
            $table->string('tariff_no')->nullable();
            $table->string('ean')->nullable()->index();
            $table->unsignedSmallInteger('wash_degrees')->nullable();
            $table->string('gender')->nullable()->index();
            $table->unsignedSmallInteger('gsm')->nullable();
            $table->longText('composition')->nullable();
            $table->longText('specifications')->nullable();
            $table->string('colour_code')->nullable();
            $table->string('colour_name_api')->nullable();
            $table->string('pantone')->nullable();
            $table->string('hex_code')->nullable();
            $table->string('size_name_api')->nullable();
            $table->decimal('price_single', 8, 2)->nullable(); 
            $table->unsignedSmallInteger('qty_single')->nullable();
            $table->decimal('price_pack', 8, 2)->nullable();
            $table->unsignedSmallInteger('pack_qty')->nullable();
            $table->decimal('price_caton', 8, 2)->nullable();
            $table->unsignedSmallInteger('carton_qty')->nullable();
            $table->decimal('price_1k', 8, 2)->nullable();
            $table->unsignedInteger('quantity_api')->nullable();
            $table->decimal('my_price', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('small_image')->nullable();
            $table->string('colour_image')->nullable();
            $table->string('sm_colour_image')->nullable();
            $table->string('video_link')->nullable();
            $table->string('packaging')->nullable();
            $table->string('country_of_origin')->nullable()->index();
            $table->decimal('gross_weight', 5, 2)->nullable();
            $table->decimal('net_weight', 5, 2)->nullable();
            $table->string('tax_code')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
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
