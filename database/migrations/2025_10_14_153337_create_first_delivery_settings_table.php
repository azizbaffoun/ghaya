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
        Schema::create('first_delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->string('first_delivery_key')->nullable();
            $table->decimal('delivery_cost', 10, 2)->default(0);
            $table->decimal('return_cost', 10, 2)->default(0);
            $table->string('store_name')->nullable();
            $table->string('store_phone')->nullable();
            $table->text('store_address')->nullable();
            $table->string('store_city')->nullable();
            $table->string('vat_number')->nullable();
            $table->boolean('allow_open_package')->default(false);
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_delivery_settings');
    }
};
