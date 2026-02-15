<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daily Attendance - {{ \Carbon\Carbon::parse($date)->format('d M Y (l)') }}</title>
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
            margin-bottom: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 5px 8px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            font-size: 11px;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
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

        .badge-secondary {
            background: #6c757d;
        }

        .badge-light {
            background: #e9ecef;
            color: #333;
        }

        .off-day {
            background: #fff8e1;
        }

        .summary {
            margin-top: 15px;
            font-size: 12px;
        }

        .summary span {
            margin-right: 20px;
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
        <h2>Daily Attendance Sheet</h2>
        <h3>{{ \Carbon\Carbon::parse($date)->format('d F Y — l') }}</h3>
        <p>Generated: {{ now()->format('d M Y h:i A') }}</p>
    </div>

    @php
        $presentCount = 0;
        $absentCount = 0;
        $offDayCount = 0;
        $noRecordCount = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>Role</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>OVER (min)</th>
                <th>Late (min)</th>
                <th>Penalty</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $i => $user)
                @php
                    $attendance = $attendances->get($user->id);
                    $isOffDay = $user->isOffDay($date);

                    if ($attendance) {
                        if ($attendance->status == 'present') {
                            $presentCount++;
                        } else {
                            $absentCount++;
                        }
                    } elseif ($isOffDay) {
                        $offDayCount++;
                    } else {
                        $noRecordCount++;
                    }
                @endphp
                <tr class="{{ $isOffDay ? 'off-day' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role == 1 ? 'Admin' : ($user->role == 2 ? 'Manager' : 'Employee') }}</td>
                    <td>{{ $user->start_time ?? 'N/A' }} - {{ $user->end_time ?? 'N/A' }}</td>
                    <td>
                        @if ($attendance)
                            @if ($attendance->status == 'present')
                                <span class="badge badge-success">Present</span>
                                @if ($attendance->is_off_day)
                                    <span class="badge badge-warning">Off-day</span>
                                @endif
                                @if ($attendance->auto_checkout)
                                    <span class="badge badge-danger">Auto-out</span>
                                @endif
                            @else
                                <span class="badge badge-danger">Absent</span>
                            @endif
                        @elseif ($isOffDay)
                            <span class="badge badge-secondary">Off Day</span>
                        @else
                            <span class="badge badge-light">No Record</span>
                        @endif
                    </td>
                    <td>{{ $attendance?->check_in?->format('h:i A') ?? '-' }}</td>
                    <td>{{ $attendance?->check_out?->format('h:i A') ?? '-' }}</td>
                    <td>{{ $attendance?->overtime_minutes ?? 0 }}</td>
                    <td>{{ $attendance?->late_minutes ?? 0 }}</td>
                    <td>{{ $attendance && $attendance->penalty_amount > 0 ? '৳' . number_format($attendance->penalty_amount, 2) : '-' }}
                    </td>
                    <td>{{ $attendance?->note ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <strong>Summary:</strong>
        <span>👤 Total: {{ $users->count() }}</span>
        <span>✅ Present: {{ $presentCount }}</span>
        <span>❌ Absent/Marked: {{ $absentCount }}</span>
        <span>📅 Off Day: {{ $offDayCount }}</span>
        <span>⚪ No Record: {{ $noRecordCount }}</span>
    </div>
</body>

</html>
