<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incomplete_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique()->index();          // session-scoped token
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();

            // Live-typed fields (all nullable so partial input is OK)
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();

            // Shipping + totals
            $table->string('shipping_method_label')->nullable();
            $table->unsignedInteger('shipping_amount')->nullable();  // store in Taka (integer)
            $table->unsignedInteger('sub_total')->nullable();
            $table->unsignedInteger('total')->nullable();

            // Product tracking
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->string('product_slug')->nullable()->index();

            // Optional snapshot of the cart
            $table->json('cart_snapshot')->nullable();

            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomplete_orders');
    }
};

