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
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'package')) {
                $table->string('package')->nullable()->after('size');
            }
        });

        // Migrate any previous pack/pcs stored in size to package
        \Illuminate\Support\Facades\DB::table('carts')
            ->whereNotNull('size')
            ->where(function ($query) {
                $query->where('size', 'like', '%pcs%')
                      ->orWhere('size', 'like', '%Pcs%')
                      ->orWhere('size', 'like', '%pack%')
                      ->orWhere('size', 'like', '%Pack%')
                      ->orWhere('size', 'like', '%pis%')
                      ->orWhere('size', 'like', '%PIS%');
            })
            ->update([
                'package' => \Illuminate\Support\Facades\DB::raw('size'),
                'size' => null,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'package')) {
                $table->dropColumn('package');
            }
        });
    }
};
