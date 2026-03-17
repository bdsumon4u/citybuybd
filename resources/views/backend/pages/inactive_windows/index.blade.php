@extends('backend.layout.template')

@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>User Inactivity Monitor</h4>
            <p class="mg-b-0">Track inactive windows (gaps &gt; 5 minutes) for all staff members</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">

            {{-- ── Filters ── --}}
            <form method="GET" action="{{ route('admin.inactive-windows.index') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-control">
                            <option value="">All Users</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                    ({{ $u->role == 1 ? 'Admin' : ($u->role == 2 ? 'Manager' : 'Employee') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="gap-2 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.inactive-windows.index') }}" class="ml-2 btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- ── Summary Cards ── --}}
            <div class="mb-4 row">
                <div class="col-md-3">
                    <div class="text-white card" style="background: #dc3545;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Total Inactive Windows</div>
                                <div class="tx-28 fw-bold">{{ $totalCount }}</div>
                            </div>
                            <i class="opacity-75 fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card" style="background: #fd7e14;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Total Inactive Minutes</div>
                                <div class="tx-28 fw-bold">{{ number_format($totalMinutes) }}</div>
                            </div>
                            <i class="opacity-75 fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card" style="background: #6f42c1;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Total Inactive Hours</div>
                                <div class="tx-28 fw-bold">{{ number_format($totalMinutes / 60, 1) }}</div>
                            </div>
                            <i class="opacity-75 fas fa-hourglass-half fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card" style="background: #198754;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Users Affected</div>
                                <div class="tx-28 fw-bold">{{ $perUser->count() }}</div>
                            </div>
                            <i class="opacity-75 fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Charts ── --}}
            @if ($perUser->isNotEmpty())
                <div class="mb-4 row">
                    {{-- Bar Chart: Inactive Window Count per User --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="bg-white card-header fw-bold">
                                <i class="fas fa-chart-bar text-danger"></i> Inactive Window Count per User
                            </div>
                            <div class="p-0 card-body">
                                <div id="chart-count" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Bar Chart: Inactive Minutes per User --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="bg-white card-header fw-bold">
                                <i class="fas fa-chart-bar text-warning"></i> Total Inactive Minutes per User
                            </div>
                            <div class="p-0 card-body">
                                <div id="chart-minutes" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline / Scatter Chart --}}
                @if ($timelineData->isNotEmpty())
                    <div class="mb-4 card">
                        <div class="bg-white card-header fw-bold">
                            <i class="fas fa-chart-line text-primary"></i> Inactivity Timeline
                            <small class="ml-2 text-muted">(each bubble = one inactive window; size = duration)</small>
                        </div>
                        <div class="p-0 card-body">
                            <div id="chart-timeline" style="height: 380px;"></div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="mb-4 row">
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="bg-white card-header fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-circle text-success"></i> Online Users</span>
                            <span class="badge badge-success">{{ $onlineUsers->count() }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Last Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($onlineUsers as $u)
                                        <tr>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->role == 1 ? 'Admin' : ($u->role == 2 ? 'Manager' : 'Employee') }}
                                            </td>
                                            <td>{{ optional($u->last_active_at)->format('d M Y, h:i:s A') ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-3 text-center text-muted">No users are online.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-danger">
                        <div class="bg-white card-header fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-circle text-danger"></i> Offline Users</span>
                            <span class="badge badge-danger">{{ $offlineUsers->count() }}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Last Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($offlineUsers as $u)
                                        <tr>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->role == 1 ? 'Admin' : ($u->role == 2 ? 'Manager' : 'Employee') }}
                                            </td>
                                            <td>{{ optional($u->last_active_at)->format('d M Y, h:i:s A') ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-3 text-center text-muted">No users are offline.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Data Table ── --}}
            <div class="card">
                <div class="bg-white card-header fw-bold">
                    <i class="fas fa-table text-secondary"></i> Inactive Window Records
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Inactive From</th>
                                <th>Inactive Until</th>
                                <th>Duration (min)</th>
                                <th>Duration (hrs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($windows as $i => $w)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ optional($w->user)->name ?? '—' }}</td>
                                    <td>
                                        @php $role = optional($w->user)->role; @endphp
                                        @if ($role == 1)
                                            <span class="badge badge-danger">Admin</span>
                                        @elseif ($role == 2)
                                            <span class="badge badge-primary">Manager</span>
                                        @else
                                            <span class="badge badge-dark">Employee</span>
                                        @endif
                                    </td>
                                    <td>{{ $w->inactive_from->format('d M Y, h:i A') }}</td>
                                    <td>{{ $w->inactive_until->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $w->duration_minutes >= 60 ? 'badge-danger' : ($w->duration_minutes >= 20 ? 'badge-warning' : 'badge-secondary') }}">
                                            {{ $w->duration_minutes }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($w->duration_minutes / 60, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-muted">
                                        <i class="mb-2 fas fa-check-circle fa-2x d-block text-success"></i>
                                        No inactive windows found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        (function() {
            const format12Hour = (timestamp) => {
                const date = new Date(timestamp);
                let hours = date.getHours();
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                return hours + ':' + minutes + ' ' + ampm;
            };

            const formatMonthDayTime12 = (timestamp) => {
                const date = new Date(timestamp);
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return month + '-' + day + ' ' + format12Hour(timestamp);
            };

            // ── Per-user data from PHP ──
            const perUser = @json($perUser);
            const timeline = @json($timelineData);

            if (!perUser.length) return;

            const names = perUser.map(u => u.name);
            const counts = perUser.map(u => u.count);
            const minutes = perUser.map(u => u.minutes);

            // ── Chart 1: Inactive window count per user ──
            const chartCount = echarts.init(document.getElementById('chart-count'));
            chartCount.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                grid: {
                    left: 20,
                    right: 20,
                    bottom: 60,
                    top: 40,
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: names,
                    axisLabel: {
                        rotate: 20,
                        fontSize: 11
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Windows',
                    minInterval: 1
                },
                series: [{
                    type: 'bar',
                    data: counts,
                    itemStyle: {
                        color: '#dc3545'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        fontSize: 12,
                        fontWeight: 'bold'
                    }
                }]
            });

            // ── Chart 2: Inactive minutes per user ──
            const chartMinutes = echarts.init(document.getElementById('chart-minutes'));
            chartMinutes.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: params => params[0].name + '<br/>Minutes: <b>' + params[0].value + '</b>'
                },
                grid: {
                    left: 20,
                    right: 20,
                    bottom: 60,
                    top: 40,
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: names,
                    axisLabel: {
                        rotate: 20,
                        fontSize: 11
                    }
                },
                yAxis: {
                    type: 'value',
                    name: 'Minutes'
                },
                series: [{
                    type: 'bar',
                    data: minutes,
                    itemStyle: {
                        color: '#fd7e14'
                    },
                    label: {
                        show: true,
                        position: 'top',
                        fontSize: 12,
                        fontWeight: 'bold'
                    }
                }]
            });

            // ── Chart 3: Timeline scatter ──
            if (!timeline.length) return;

            const timelineEl = document.getElementById('chart-timeline');
            if (!timelineEl) return;

            // Unique user names for Y axis
            const userNames = [...new Set(timeline.map(d => d.user))];

            const seriesData = timeline.map(d => ({
                value: [d.from_ts, userNames.indexOf(d.user), d.minutes],
                tooltip_label: d.user + '\n' + d.from + ' → ' + d.until + '\n' + d.minutes + ' min'
            }));

            const chartTimeline = echarts.init(timelineEl);
            chartTimeline.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: params => {
                        const d = timeline[params.dataIndex];
                        return '<b>' + d.user + '</b><br/>' +
                            'From: ' + formatMonthDayTime12(d.from_ts) + '<br/>' +
                            'Until: ' + formatMonthDayTime12(d.until_ts) + '<br/>' +
                            'Duration: <b>' + d.minutes + ' min</b>';
                    }
                },
                grid: {
                    left: 20,
                    right: 40,
                    bottom: 60,
                    top: 20,
                    containLabel: true
                },
                xAxis: {
                    type: 'time',
                    axisLabel: {
                        formatter: val => formatMonthDayTime12(val),
                        rotate: 20,
                        fontSize: 10
                    }
                },
                yAxis: {
                    type: 'category',
                    data: userNames,
                    axisLabel: {
                        fontSize: 12
                    }
                },
                visualMap: {
                    show: true,
                    min: 5,
                    max: Math.max(...timeline.map(d => d.minutes), 60),
                    dimension: 2,
                    orient: 'horizontal',
                    right: 10,
                    top: 4,
                    text: ['Long', 'Short'],
                    calculable: true,
                    inRange: {
                        color: ['#ffd700', '#ff8c00', '#dc3545']
                    }
                },
                series: [{
                    type: 'scatter',
                    data: seriesData.map(d => d.value),
                    symbolSize: val => Math.min(8 + val[2] / 5, 40),
                    encode: {
                        x: 0,
                        y: 1
                    }
                }]
            });

            // Responsiveness
            window.addEventListener('resize', () => {
                chartCount.resize();
                chartMinutes.resize();
                chartTimeline && chartTimeline.resize();
            });
        })();
    </script>
@endpush
