<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('product_sale_id')->constrained('product_sales')->cascadeOnDelete();
            $table->date('return_date');
            $table->text('reason')->nullable();
            $table->decimal('total_refund', 12, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('product_sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_sale_return_id')->constrained('product_sale_returns')->cascadeOnDelete();
            $table->foreignId('product_sale_item_id')->constrained('product_sale_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('refund_amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sale_return_items');
        Schema::dropIfExists('product_sale_returns');
    }
};
