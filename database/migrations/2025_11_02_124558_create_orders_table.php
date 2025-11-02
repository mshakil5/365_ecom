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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('shipping_method', ['0', '1'])->default('0'); // 0=ship, 1=pickup
            $table->string('full_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_first_line')->nullable();
            $table->string('address_second_line')->nullable();
            $table->string('address_third_line')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->text('order_notes')->nullable();
            
            // Billing address
            $table->string('billing_full_name')->nullable();
            $table->string('billing_company_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_address_first_line')->nullable();
            $table->string('billing_address_second_line')->nullable();
            $table->string('billing_address_third_line')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_postcode')->nullable();
            
            $table->string('payment_method')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('shipping_charge', 10, 2)->nullable();
            $table->decimal('vat_percent', 5, 2)->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
