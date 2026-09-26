@extends('backend.layout.template')

@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Product Distribution</h4>
            <p class="mg-b-0">Quantity of each product ordered on a selected date</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <form method="GET" action="{{ route('reports.product_distribution') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="gap-2 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('reports.product_distribution') }}" class="ml-2 btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="mb-4 row">
                <div class="col-md-4">
                    <div class="text-white card" style="background: #0d6efd;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Products In Chart</div>
                                <div class="tx-28 fw-bold">{{ $labels->count() }}</div>
                            </div>
                            <i class="opacity-75 fas fa-box-open fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-white card" style="background: #198754;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Total Ordered Quantity</div>
                                <div class="tx-28 fw-bold">{{ number_format($totalQuantity) }}</div>
                            </div>
                            <i class="opacity-75 fas fa-chart-bar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="bg-white card-header fw-bold">
                    <i class="fas fa-chart-bar text-primary"></i> Product Quantity Distribution ({{ $selectedDate }})
                </div>
                <div class="p-0 card-body">
                    <div id="product-distribution-chart" style="height: 480px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script>
        (function() {
            const labels = @json($labels);
            const values = @json($values);
            const chartElement = document.getElementById('product-distribution-chart');

            if (!chartElement || !labels.length) {
                if (chartElement) {
                    chartElement.innerHTML =
                        '<div class="py-5 text-center text-muted">No product orders found for this date.</div>';
                }
                return;
            }

            chartElement.style.height = Math.max(420, labels.length * 36) + 'px';
            const chart = echarts.init(chartElement);

            function updateChart() {
                const isDark = document.body.classList.contains('dark-theme') ||
                               document.documentElement.classList.contains('dark-theme') ||
                               localStorage.getItem('admin_theme') === 'dark';

                const textColor = isDark ? '#ffffff' : '#334155';
                const subTextColor = isDark ? '#cbd5e1' : '#64748b';
                const lineColor = isDark ? 'rgba(255, 255, 255, 0.25)' : '#cbd5e1';
                const splitLineColor = isDark ? 'rgba(255, 255, 255, 0.08)' : '#e2e8f0';

                chart.setOption({
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        borderColor: isDark ? '#475569' : '#e2e8f0',
                        textStyle: {
                            color: isDark ? '#ffffff' : '#0f172a'
                        },
                        axisPointer: {
                            type: 'shadow'
                        },
                        formatter: params => params[0].name + '<br/>Quantity: <b>' + params[0].value + '</b>'
                    },
                    grid: {
                        left: 20,
                        right: 70,
                        top: 20,
                        bottom: 20,
                        containLabel: true
                    },
                    xAxis: {
                        type: 'value',
                        name: 'Quantity',
                        minInterval: 1,
                        nameTextStyle: {
                            color: subTextColor
                        },
                        axisLabel: {
                            color: subTextColor
                        },
                        axisLine: {
                            lineStyle: {
                                color: lineColor
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                color: splitLineColor
                            }
                        }
                    },
                    yAxis: {
                        type: 'category',
                        data: labels,
                        axisLabel: {
                            color: textColor,
                            fontSize: 12,
                            fontWeight: isDark ? 500 : 400
                        },
                        axisLine: {
                            lineStyle: {
                                color: lineColor
                            }
                        },
                        axisTick: {
                            lineStyle: {
                                color: lineColor
                            }
                        }
                    },
                    series: [{
                        type: 'bar',
                        data: values,
                        itemStyle: {
                            color: '#0d6efd'
                        },
                        label: {
                            show: true,
                            position: 'right',
                            color: textColor,
                            fontWeight: 'bold',
                            fontSize: 12
                        }
                    }]
                });
            }

            updateChart();

            window.addEventListener('resize', () => chart.resize());
            window.addEventListener('themeChanged', updateChart);
            window.addEventListener('storage', (e) => {
                if (e.key === 'admin_theme') updateChart();
            });
        })();
    </script>
@endpush
