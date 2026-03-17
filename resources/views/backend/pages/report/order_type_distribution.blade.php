@extends('backend.layout.template')

@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>OrderType Distribution</h4>
            <p class="mg-b-0">Number of orders by order type on a selected date</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <form method="GET" action="{{ route('reports.order_type_distribution') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $selectedDate }}">
                    </div>
                    <div class="gap-2 col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('reports.order_type_distribution') }}" class="ml-2 btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="mb-4 row">
                <div class="col-md-4">
                    <div class="text-white card" style="background: #fd7e14;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Order Types In Chart</div>
                                <div class="tx-28 fw-bold">{{ $labels->count() }}</div>
                            </div>
                            <i class="opacity-75 fas fa-layer-group fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-white card" style="background: #6f42c1;">
                        <div class="py-3 card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-1 tx-12 text-uppercase">Total Orders</div>
                                <div class="tx-28 fw-bold">{{ number_format($totalOrders) }}</div>
                            </div>
                            <i class="opacity-75 fas fa-receipt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="bg-white card-header fw-bold">
                    <i class="fas fa-chart-bar text-warning"></i> OrderType Distribution ({{ $selectedDate }})
                </div>
                <div class="p-0 card-body">
                    <div id="order-type-distribution-chart" style="height: 420px;"></div>
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
            const chartElement = document.getElementById('order-type-distribution-chart');

            if (!chartElement || !labels.length) {
                if (chartElement) {
                    chartElement.innerHTML =
                        '<div class="py-5 text-center text-muted">No orders found for this date.</div>';
                }
                return;
            }

            chartElement.style.height = Math.max(360, labels.length * 42) + 'px';
            const chart = echarts.init(chartElement);

            chart.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    },
                    formatter: params => params[0].name + '<br/>Orders: <b>' + params[0].value + '</b>'
                },
                grid: {
                    left: 20,
                    right: 60,
                    top: 20,
                    bottom: 20,
                    containLabel: true
                },
                xAxis: {
                    type: 'value',
                    name: 'Orders',
                    minInterval: 1
                },
                yAxis: {
                    type: 'category',
                    data: labels,
                    axisLabel: {
                        fontSize: 12
                    }
                },
                series: [{
                    type: 'bar',
                    data: values,
                    itemStyle: {
                        color: '#fd7e14'
                    },
                    label: {
                        show: true,
                        position: 'right'
                    }
                }]
            });

            window.addEventListener('resize', () => chart.resize());
        })();
    </script>
@endpush
