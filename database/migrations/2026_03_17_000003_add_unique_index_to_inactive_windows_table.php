<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep the earliest row per duplicate tuple so the unique index can be created safely.
        $duplicateGroups = DB::table('inactive_windows')
            ->select(
                'user_id',
                'inactive_from',
                'inactive_until',
                DB::raw('MIN(id) as keep_id'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('user_id', 'inactive_from', 'inactive_until')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicateGroups as $group) {
            DB::table('inactive_windows')
                ->where('user_id', $group->user_id)
                ->where('inactive_from', $group->inactive_from)
                ->where('inactive_until', $group->inactive_until)
                ->where('id', '!=', $group->keep_id)
                ->delete();
        }

        Schema::table('inactive_windows', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'inactive_from', 'inactive_until'],
                'inactive_windows_user_from_until_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('inactive_windows', function (Blueprint $table) {
            $table->dropUnique('inactive_windows_user_from_until_unique');
        });
    }
};
