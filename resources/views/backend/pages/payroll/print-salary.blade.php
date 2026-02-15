<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Salary Sheet - {{ $payroll->user->name }} - {{ $payroll->month_name }} {{ $payroll->year }}</title>
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

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info-section .box {
            width: 48%;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .info-section .box h4 {
            font-size: 13px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        .info-section .box p {
            margin: 3px 0;
            font-size: 12px;
        }

        .info-section .box strong {
            display: inline-block;
            width: 140px;
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

        .badge-primary {
            background: #007bff;
        }

        .badge-secondary {
            background: #6c757d;
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

        .salary-summary {
            margin-top: 15px;
            width: 350px;
            margin-left: auto;
        }

        .salary-summary td {
            border: none;
            padding: 3px 8px;
            font-size: 12px;
        }

        .salary-summary .total {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 14px;
        }

        .salary-summary .add {
            color: #28a745;
        }

        .salary-summary .sub {
            color: #dc3545;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }

        .signature-area {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-area div {
            text-align: center;
            width: 200px;
        }

        .signature-area .line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 12px;
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
        <h2>Salary Sheet</h2>
        <h3>{{ $payroll->user->name }} — {{ $payroll->month_name }} {{ $payroll->year }}</h3>
        <p>Generated: {{ now()->format('d M Y h:i A') }} | Status:
            @if ($payroll->status == 'paid')
                PAID
            @elseif ($payroll->status == 'approved')
                APPROVED
            @else
                DRAFT
            @endif
        </p>
    </div>

    <div class="info-section">
        <div class="box">
            <h4>Employee Information</h4>
            <p><strong>Name:</strong> {{ $payroll->user->name }}</p>
            <p><strong>Role:</strong>
                {{ $payroll->user->role == 1 ? 'Admin' : ($payroll->user->role == 2 ? 'Manager' : 'Employee') }}</p>
            <p><strong>Daily Salary:</strong> ৳{{ number_format($payroll->daily_salary, 2) }}</p>
            <p><strong>Schedule:</strong> {{ $payroll->user->start_time ?? config('attendance.default_start_time') }} -
                {{ $payroll->user->end_time ?? config('attendance.default_end_time') }}</p>
            <p><strong>Off Days:</strong> {{ $payroll->user->off_days ?? 'None' }}</p>
        </div>
        <div class="box">
            <h4>Attendance Summary</h4>
            <p><strong>Total Days:</strong> {{ $payroll->total_days }}</p>
            <p><strong>Working Days:</strong> {{ $payroll->working_days }}</p>
            <p><strong>Present Days:</strong> {{ $payroll->present_days }}</p>
            <p><strong>Absent Days:</strong> {{ $payroll->absent_days }}</p>
            <p><strong>Off-day Work:</strong> {{ $payroll->off_day_presents }} days</p>
        </div>
    </div>

    <div class="section-title">Attendance Records</div>
    @php
        $unitMin = max($paySettings->overtime_unit_minutes, 1);
        $otRate = $paySettings->overtime_rate;
        $lateUnitMin = max($paySettings->latetime_unit_minutes ?? $unitMin, 1);
        $lateRate = $paySettings->latetime_rate ?? $otRate;
        $dSalary = $payroll->daily_salary;
        $sStart = \Carbon\Carbon::parse($payroll->user->start_time ?? config('attendance.default_start_time'));
        $sEnd = \Carbon\Carbon::parse($payroll->user->end_time ?? config('attendance.default_end_time'));
        $schedMin = abs($sEnd->diffInMinutes($sStart));
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
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($advances->count() > 0)
        <div class="section-title">Salary Advances</div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Approved By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($advances as $adv)
                    <tr>
                        <td>{{ $adv->date->format('d M Y') }}</td>
                        <td>৳{{ number_format($adv->amount, 2) }}</td>
                        <td>{{ $adv->note ?? '-' }}</td>
                        <td>{{ $adv->approver->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">Salary Calculation</div>
    <table class="salary-summary">
        <tr>
            <td>Base Salary ({{ $payroll->present_days - $payroll->off_day_presents }} days ×
                ৳{{ number_format($payroll->daily_salary, 2) }})</td>
            <td style="text-align: right;">৳{{ number_format($payroll->base_salary, 2) }}</td>
        </tr>
        <tr class="add">
            <td>+ Off-day Bonus ({{ $payroll->off_day_presents }} days × 1.5 ×
                ৳{{ number_format($payroll->daily_salary, 2) }})</td>
            <td style="text-align: right;">৳{{ number_format($payroll->off_day_bonus, 2) }}</td>
        </tr>
        <tr class="add">
            <td>+ Overtime Bonus</td>
            <td style="text-align: right;">৳{{ number_format($payroll->overtime_amount, 2) }}</td>
        </tr>
        <tr class="sub">
            <td>− Late Fee Deduction</td>
            <td style="text-align: right;">৳{{ number_format($payroll->late_deduction, 2) }}</td>
        </tr>
        <tr class="sub">
            <td>− Penalty Deduction</td>
            <td style="text-align: right;">৳{{ number_format($payroll->penalty_amount, 2) }}</td>
        </tr>
        <tr class="sub">
            <td>− Advance Deduction</td>
            <td style="text-align: right;">৳{{ number_format($payroll->advance_deduction, 2) }}</td>
        </tr>
        <tr class="total">
            <td>NET SALARY</td>
            <td style="text-align: right;">৳{{ number_format($payroll->net_salary, 2) }}</td>
        </tr>
    </table>

    <div class="signature-area">
        <div>
            <div class="line">Employee Signature</div>
        </div>
        <div>
            <div class="line">Authorized Signature</div>
        </div>
    </div>
</body>

</html>
