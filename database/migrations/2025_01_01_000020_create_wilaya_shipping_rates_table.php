<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilaya_shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('wilaya_code', 5);
            $table->string('wilaya_name');
            $table->decimal('domicile_cost', 8, 2)->default(700);
            $table->decimal('stop_desk_cost', 8, 2)->default(500);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['shop_id', 'wilaya_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilaya_shipping_rates');
    }
};
