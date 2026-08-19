<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->unique();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('customer_type', ['member', 'walk_in'])->default('walk_in');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('profit', 12, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('paid');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('sale_date');
            $table->timestamps();

            $table->index('sale_date');
            $table->index('payment_status');
        });

        Schema::create('product_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_sale_id')->constrained('product_sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('total', 12, 2);
            $table->decimal('profit', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sale_items');
        Schema::dropIfExists('product_sales');
    }
};
