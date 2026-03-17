<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InactiveWindow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InactiveWindowController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Only staff users (admin/manager/employee)
        $users = User::whereIn('role', [1, 2, 3])
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'role', 'last_active_at']);

        $query = InactiveWindow::with('user:id,name,role');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('inactive_from', $request->date);
        }

        $windows = $query->orderBy('inactive_from', 'desc')->get();

        $totalCount = $windows->count();
        $totalMinutes = $windows->sum('duration_minutes');

        // Per-user summary for the bar chart
        $perUser = $windows->groupBy('user_id')->map(function ($group) {
            return [
                'name' => optional($group->first()->user)->name ?? 'Unknown',
                'count' => $group->count(),
                'minutes' => $group->sum('duration_minutes'),
            ];
        })->values();

        // Timeline data for ECharts scatter/timeline chart:
        // Each point: [user_name, inactive_from (unix ms), inactive_until (unix ms), duration_minutes]
        $timelineData = $windows->map(function ($w) {
            return [
                'user' => optional($w->user)->name ?? 'Unknown',
                'from' => $w->inactive_from->format('Y-m-d H:i:s'),
                'until' => $w->inactive_until->format('Y-m-d H:i:s'),
                'minutes' => $w->duration_minutes,
                'from_ts' => $w->inactive_from->timestamp * 1000,
                'until_ts' => $w->inactive_until->timestamp * 1000,
            ];
        });

        $onlineUsers = $users->filter(function ($user) use ($now) {
            if ($user->last_active_at === null) {
                return false;
            }

            return $user->last_active_at->diffInMinutes($now) < 5;
        })->values();

        $offlineUsers = $users->reject(function ($user) use ($now) {
            if ($user->last_active_at === null) {
                return false;
            }

            return $user->last_active_at->diffInMinutes($now) < 5;
        })->values();

        return view('backend.pages.inactive_windows.index', compact(
            'users',
            'windows',
            'totalCount',
            'totalMinutes',
            'perUser',
            'timelineData',
            'onlineUsers',
            'offlineUsers'
        ));
    }
}
