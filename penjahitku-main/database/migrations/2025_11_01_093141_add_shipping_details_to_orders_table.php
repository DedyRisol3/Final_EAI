<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan detail pengiriman baru
            $table->string('province_id')->nullable()->after('status');
            $table->string('city_id')->nullable()->after('province_id');
            $table->string('district_id')->nullable()->after('city_id');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('district_id');
            $table->string('shipping_etd')->nullable()->after('shipping_cost');
            $table->text('address_line_1')->nullable()->after('shipping_etd'); 
        });
    }

    
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'province_id',
                'city_id',
                'district_id',
                'shipping_cost',
                'shipping_etd',
                'address_line_1',
            ]);
        });
    }
};
