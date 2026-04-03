<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('niche_id')
                ->nullable()
                ->after('supplier_id')
                ->constrained('niches')
                ->nullOnDelete();

            $table->decimal('shipping_cost', 10, 2)->nullable()->after('price');
            $table->decimal('discount', 10, 2)->nullable()->after('shipping_cost');
            $table->decimal('product_cost', 10, 2)->nullable()->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('niche_id');
            $table->dropColumn(['shipping_cost', 'discount', 'product_cost']);
        });
    }
};

