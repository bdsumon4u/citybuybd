<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OvertimeRequest;
use App\Models\User;
use App\Services\OvertimeRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OvertimeRequestController extends Controller
{
    public function __construct(
        protected OvertimeRequestService $service
    ) {}

    public function index(Request $request): View
    {
        $status = $request->get('status');
        $userId = $request->get('user_id');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::whereIn('role', [1, 2, 3])->orderBy('name')->get();

        $query = OvertimeRequest::with(['user', 'approver'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($status && in_array($status, [OvertimeRequest::STATUS_PENDING, OvertimeRequest::STATUS_APPROVED, OvertimeRequest::STATUS_REJECTED], true)) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($month && $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        }

        $requests = $query->paginate(30)->appends($request->query());

        $pendingCount = OvertimeRequest::pending()->count();
        $monthlyApprovedMinutes = (int) OvertimeRequest::approved()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('approved_minutes');
        $monthlyRejectedCount = OvertimeRequest::rejected()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();

        return view('backend.pages.attendance.overtime_requests', compact(
            'requests',
            'users',
            'status',
            'userId',
            'month',
            'year',
            'pendingCount',
            'monthlyApprovedMinutes',
            'monthlyRejectedCount'
        ));
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'approved_minutes' => 'required|integer|min:0|max:1440',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $overtimeRequest = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        $this->service->approveRequest(
            $overtimeRequest,
            $user,
            (int) $request->approved_minutes,
            $request->admin_note
        );

        return back()->with('message', "Overtime request approved for {$overtimeRequest->user->name} ({$request->approved_minutes} min).");
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $overtimeRequest = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        $this->service->rejectRequest(
            $overtimeRequest,
            $user,
            $request->admin_note
        );

        return back()->with('message', "Overtime request rejected for {$overtimeRequest->user->name}.");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'approved_minutes' => 'required|integer|min:0|max:1440',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $overtimeRequest = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        $this->service->updateRequest(
            $overtimeRequest,
            $user,
            (int) $request->approved_minutes,
            $request->admin_note
        );

        return back()->with('message', "Overtime record updated for {$overtimeRequest->user->name}.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $overtimeRequest = OvertimeRequest::findOrFail($id);
        $userName = $overtimeRequest->user->name ?? 'Staff';

        $this->service->deleteRequest($overtimeRequest);

        return back()->with('message', "Overtime request deleted for {$userName}.");
    }
}
