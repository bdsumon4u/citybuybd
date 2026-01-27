<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('master_id')->nullable()->after('id');
            $table->unsignedInteger('slave_id')->nullable()->after('master_id');
            $table->string('slave_domain')->nullable()->after('slave_id');
            $table->string('forwarding_status')->default('pending')->after('order_type');
            $table->text('forwarding_error')->nullable()->after('forwarding_status');

            $table->unique(['slave_domain', 'slave_id'], 'orders_slave_domain_slave_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_slave_domain_slave_id_unique');
            $table->dropColumn([
                'master_id',
                'slave_id',
                'slave_domain',
                'forwarding_status',
                'forwarding_error',
            ]);
        });
    }
};
