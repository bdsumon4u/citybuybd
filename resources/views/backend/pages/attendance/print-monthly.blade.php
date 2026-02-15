<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance - {{ $user->name }} - {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            font-size: 20px;
            margin-bottom: 2px;
        }

        .header h3 {
            font-size: 16px;
            font-weight: normal;
            color: #555;
        }

        .header p {
            font-size: 12px;
            color: #777;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .info-row .left,
        .info-row .right {
            width: 48%;
        }

        .info-row strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            font-size: 11px;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            display: inline-block;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-danger {
            background: #dc3545;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .off-day {
            background: #fff8e1;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .summary-table {
            width: auto;
            margin-top: 15px;
        }

        .summary-table td {
            border: none;
            padding: 2px 15px 2px 0;
        }

        .no-print {
            margin-bottom: 15px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()"
            style="padding:8px 20px; background:#007bff; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:14px;">
            🖨️ Print
        </button>
        <button onclick="window.close()"
            style="padding:8px 20px; background:#6c757d; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:14px; margin-left:5px;">
            ✕ Close
        </button>
    </div>

    <div class="header">
        <h2>Monthly Attendance Sheet</h2>
        <h3>{{ $user->name }} — {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</h3>
        <p>Generated: {{ now()->format('d M Y h:i A') }}</p>
    </div>

    <div class="info-row">
        <div class="left">
            <p><strong>Employee:</strong> {{ $user->name }}</p>
            <p><strong>Role:</strong> {{ $user->role == 1 ? 'Admin' : ($user->role == 2 ? 'Manager' : 'Employee') }}</p>
            <p><strong>Daily Salary:</strong> ৳{{ number_format($user->daily_salary, 2) }}</p>
        </div>
        <div class="right">
            <p><strong>Schedule:</strong> {{ $user->start_time ?? config('attendance.default_start_time') }} -
                {{ $user->end_time ?? config('attendance.default_end_time') }}</p>
            <p><strong>Off Days:</strong> {{ $user->off_days ?? 'None' }}</p>
            <p><strong>Total Days:</strong> {{ $totalDays }}</p>
        </div>
    </div>

    @php
        $unitMin = max($paySettings->overtime_unit_minutes, 1);
        $otRate = $paySettings->overtime_rate;
        $lateUnitMin = max($paySettings->latetime_unit_minutes ?? $unitMin, 1);
        $lateRate = $paySettings->latetime_rate ?? $otRate;
        $dSalary = $user->daily_salary;
        $sStart = \Carbon\Carbon::parse($user->start_time ?? config('attendance.default_start_time'));
        $sEnd = \Carbon\Carbon::parse($user->end_time ?? config('attendance.default_end_time'));
        $schedMin = abs($sEnd->diffInMinutes($sStart));

        $totalOT = 0;
        $totalLate = 0;
        $totalOTAmount = 0;
        $totalLateAmount = 0;
        $totalPenalty = 0;
        $presentCount = 0;
        $absentCount = 0;
        $offDayPresent = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>OVER (min)</th>
                <th>OVER ৳</th>
                <th>Late (min)</th>
                <th>Late ৳</th>
                <th>Penalty</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $att)
                @php
                    $dailyOT = floor(($att->overtime_minutes ?? 0) / $unitMin) * $otRate;
                    $dailyLate = floor(($att->late_minutes ?? 0) / $lateUnitMin) * $lateRate;
                    $cap = $dSalary;
                    if ($att->check_in && $att->check_out && $schedMin > 0) {
                        $worked = abs(
                            \Carbon\Carbon::parse($att->check_out)->diffInMinutes(
                                \Carbon\Carbon::parse($att->check_in),
                            ),
                        );
                        if ($worked >= $schedMin / 2) {
                            $cap = $dSalary / 2;
                        }
                    }
                    $dailyLate = min($dailyLate, $cap);

                    if ($att->status == 'present') {
                        $presentCount++;
                        if ($att->is_off_day) {
                            $offDayPresent++;
                        }
                    } else {
                        $absentCount++;
                    }
                    $totalOT += $att->overtime_minutes ?? 0;
                    $totalLate += $att->late_minutes ?? 0;
                    $totalOTAmount += $dailyOT;
                    $totalLateAmount += $dailyLate;
                    $totalPenalty += $att->penalty_amount ?? 0;
                @endphp
                <tr class="{{ $att->is_off_day ? 'off-day' : '' }}">
                    <td>{{ $att->date->format('d M') }}</td>
                    <td>{{ $att->date->format('D') }}</td>
                    <td>
                        <span
                            class="badge badge-{{ $att->status == 'present' ? 'success' : 'danger' }}">{{ ucfirst($att->status) }}</span>
                        @if ($att->is_off_day)
                            <span class="badge badge-warning">Off</span>
                        @endif
                        @if ($att->auto_checkout)
                            <span class="badge badge-danger">Auto</span>
                        @endif
                    </td>
                    <td>{{ $att->check_in?->format('h:i A') ?? '-' }}</td>
                    <td>{{ $att->check_out?->format('h:i A') ?? '-' }}</td>
                    <td>{{ $att->overtime_minutes }}</td>
                    <td class="text-success">{{ $dailyOT > 0 ? '৳' . number_format($dailyOT, 2) : '-' }}</td>
                    <td>{{ $att->late_minutes }}</td>
                    <td class="text-danger">{{ $dailyLate > 0 ? '৳' . number_format($dailyLate, 2) : '-' }}</td>
                    <td>{{ $att->penalty_amount > 0 ? '৳' . number_format($att->penalty_amount, 2) : '-' }}</td>
                    <td>{{ $att->note }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f0f0f0;">
                <td colspan="5" style="text-align: right;">TOTALS:</td>
                <td>{{ $totalOT }}</td>
                <td class="text-success">৳{{ number_format($totalOTAmount, 2) }}</td>
                <td>{{ $totalLate }}</td>
                <td class="text-danger">৳{{ number_format($totalLateAmount, 2) }}</td>
                <td class="text-danger">৳{{ number_format($totalPenalty, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table class="summary-table">
        <tr>
            <td><strong>Present:</strong> {{ $presentCount }}</td>
            <td><strong>Absent:</strong> {{ $absentCount }}</td>
            <td><strong>Off-day Work:</strong> {{ $offDayPresent }}</td>
            <td><strong>Total OVER:</strong> ৳{{ number_format($totalOTAmount, 2) }}</td>
            <td><strong>Total Late:</strong> ৳{{ number_format($totalLateAmount, 2) }}</td>
            <td><strong>Total Penalty:</strong> ৳{{ number_format($totalPenalty, 2) }}</td>
        </tr>
    </table>
</body>

</html>
