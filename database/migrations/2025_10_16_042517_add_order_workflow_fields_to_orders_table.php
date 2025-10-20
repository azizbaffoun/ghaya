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
            // confirmation_status is now in the base migration
            $table->string('print_url')->nullable()->after('first_delivery_id');
            $table->string('barcode')->nullable()->after('print_url');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('pickup_requested_at')->nullable();
            $table->timestamp('reminder_at')->nullable();
            $table->text('staff_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'print_url',
                'barcode',
                'confirmed_at',
                'printed_at',
                'pickup_requested_at',
                'reminder_at',
                'staff_notes'
            ]);
        });
    }
};
