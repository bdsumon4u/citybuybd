@extends('backend.layout.template')

@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>Courier Invoiced Products</h4>
            <p class="mg-b-0">Product quantity distribution for orders in Courier Entry and Printed Invoice statuses</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <form method="GET" action="{{ route('reports.courier_invoiced_products') }}" class="mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <label class="form-label">Products</label>
                        <select name="product_ids[]" class="form-control select2" multiple>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ $selectedProductIds->contains($product->id) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Search and select one or more products</small>
                    </div>
                    <div class="col-md-4">
                        <div class="gap-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('reports.courier_invoiced_products') }}" class="ml-2 btn btn-secondary">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
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
                    <i class="fas fa-chart-bar text-primary"></i> Courier Invoiced Product Distribution
                    <small class="ml-2 text-muted">(All dates, statuses: Courier Entry + Printed Invoice)</small>
                </div>
                <div class="p-0 card-body">
                    <div id="courier-invoiced-products-chart" style="height: 480px;"></div>
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
            const chartElement = document.getElementById('courier-invoiced-products-chart');

            if (!chartElement || !labels.length) {
                if (chartElement) {
                    chartElement.innerHTML =
                        '<div class="py-5 text-center text-muted">No courier-entry or printed-invoice product data found.</div>';
                }
                return;
            }

            chartElement.style.height = Math.max(420, labels.length * 36) + 'px';
            const chart = echarts.init(chartElement);

            chart.setOption({
                tooltip: {
                    trigger: 'axis',
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
                    minInterval: 1
                },
                yAxis: {
                    type: 'category',
                    data: labels,
                    axisLabel: {
                        fontSize: 11
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
                        position: 'right'
                    }
                }]
            });

            window.addEventListener('resize', () => chart.resize());
        })();
    </script>
@endpush
