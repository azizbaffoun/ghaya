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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('first_delivery_id')->nullable()->after('delivered_at');
            $table->string('first_delivery_tracking_number')->nullable()->after('first_delivery_id');
            $table->string('first_delivery_status')->nullable()->after('first_delivery_tracking_number');
            $table->json('first_delivery_response')->nullable()->after('first_delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'first_delivery_id',
                'first_delivery_tracking_number',
                'first_delivery_status',
                'first_delivery_response'
            ]);
        });
    }
};
