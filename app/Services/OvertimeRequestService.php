<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OvertimeRequestService
{
    public function submitRequest(User $user, string $date, int $minutes, ?string $reason = null): OvertimeRequest
    {
        return OvertimeRequest::create([
            'user_id' => $user->id,
            'date' => $date,
            'requested_minutes' => max(1, $minutes),
            'reason' => $reason,
            'status' => OvertimeRequest::STATUS_PENDING,
        ]);
    }

    public function approveRequest(
        OvertimeRequest $request,
        User $admin,
        int $approvedMinutes,
        ?string $adminNote = null
    ): OvertimeRequest {
        return DB::transaction(function () use ($request, $admin, $approvedMinutes, $adminNote) {
            $request->update([
                'approved_minutes' => max(0, $approvedMinutes),
                'status' => OvertimeRequest::STATUS_APPROVED,
                'admin_note' => $adminNote,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $this->syncAttendanceExtraOvertime((int) $request->user_id, $request->date->toDateString());

            return $request;
        });
    }

    public function rejectRequest(
        OvertimeRequest $request,
        User $admin,
        ?string $adminNote = null
    ): OvertimeRequest {
        return DB::transaction(function () use ($request, $admin, $adminNote) {
            $request->update([
                'status' => OvertimeRequest::STATUS_REJECTED,
                'admin_note' => $adminNote,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $this->syncAttendanceExtraOvertime((int) $request->user_id, $request->date->toDateString());

            return $request;
        });
    }

    public function updateRequest(
        OvertimeRequest $request,
        User $admin,
        int $approvedMinutes,
        ?string $adminNote = null
    ): OvertimeRequest {
        return DB::transaction(function () use ($request, $admin, $approvedMinutes, $adminNote) {
            $request->update([
                'approved_minutes' => max(0, $approvedMinutes),
                'admin_note' => $adminNote,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $this->syncAttendanceExtraOvertime((int) $request->user_id, $request->date->toDateString());

            return $request;
        });
    }

    public function deleteRequest(OvertimeRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $userId = (int) $request->user_id;
            $dateStr = $request->date->toDateString();

            $request->delete();

            $this->syncAttendanceExtraOvertime($userId, $dateStr);
        });
    }

    public function syncAttendanceExtraOvertime(int $userId, string $date): void
    {
        $totalApprovedMinutes = (int) OvertimeRequest::query()
            ->where('user_id', $userId)
            ->where('date', $date)
            ->approved()
            ->sum('approved_minutes');

        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            $attendance->extra_overtime_minutes = $totalApprovedMinutes;
            $attendance->save();
        } elseif ($totalApprovedMinutes > 0) {
            $user = User::find($userId);
            if ($user) {
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'is_off_day' => $user->isOffDay($date),
                    'extra_overtime_minutes' => $totalApprovedMinutes,
                    'overtime_minutes' => 0,
                    'late_minutes' => 0,
                    'status' => 'present',
                ]);
            }
        }
    }
}
