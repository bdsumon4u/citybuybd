@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-2 card">
                        <div class="card-body">
                            <h4 class="tx-gray-800">Return Received Scanner</h4>
                            <p class="mb-0">Scan order barcode to update status from Pending Return → Return</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Scan Order</h5>
                        </div>
                        <div class="card-body">
                            <form id="scanForm">
                                <div class="form-group">
                                    <label for="order_id">Order ID</label>
                                    <input type="text" class="form-control form-control-lg" id="order_id"
                                        name="order_id" placeholder="Scan barcode or enter Order ID" autofocus>
                                    <small class="form-text text-muted">The input field is auto-focused. Scan barcode using
                                        your physical scanner.</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Scan Result</h5>
                        </div>
                        <div class="card-body">
                            <div id="scanResult"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Today's Scanned Orders</h5>
                            <div>
                                <form method="GET" action="{{ route('order.printScannedOrders') }}" class="form-inline">
                                    <input type="date" id="filterDate" name="date" class="form-control d-inline-block"
                                        style="width: auto;" value="{{ date('Y-m-d') }}">
                                    <input type="hidden" name="type" value="return">
                                    <button type="submit" class="btn btn-primary ml-2">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="scannedOrdersTable">
                                    <thead>
                                        <tr>
                                            <th>SL.</th>
                                            <th>Invoice ID</th>
                                            <th>Customer Info</th>
                                            <th>COD</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ordersTableBody">
                                        <tr>
                                            <td colspan="4" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" style="text-align: right;">Total COD:</th>
                                            <th id="totalCod">0.00</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>

    <script>
        let orderIdInput;
        let filterDateInput;
        let isProcessing = false;

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            orderIdInput = document.getElementById('order_id');
            filterDateInput = document.getElementById('filterDate');

            if (orderIdInput) {
                orderIdInput.focus();
            }

            loadScannedOrders();

            // Handle form submission (Enter key from barcode scanner)
            const scanForm = document.getElementById('scanForm');
            if (scanForm) {
                scanForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const orderId = orderIdInput.value.trim();
                    if (orderId && !isProcessing) {
                        processOrder(orderId);
                    }
                });
            }

            // Also handle input event for immediate processing (some scanners don't send Enter)
            if (orderIdInput) {
                orderIdInput.addEventListener('input', function() {
                    const orderId = this.value.trim();
                    // Process if input looks complete (usually barcode scanners input quickly)
                    if (orderId && orderId.length >= 3 && !isProcessing) {
                        // Small delay to ensure full barcode is scanned
                        clearTimeout(window.scanTimeout);
                        window.scanTimeout = setTimeout(function() {
                            if (orderIdInput.value.trim() === orderId) {
                                processOrder(orderId);
                            }
                        }, 300);
                    }
                });

                // Keep input focused after any interaction
                orderIdInput.addEventListener('blur', function() {
                    setTimeout(function() {
                        orderIdInput.focus();
                    }, 100);
                });
            }

            // Filter by date
            if (filterDateInput) {
                filterDateInput.addEventListener('change', function() {
                    loadScannedOrders();
                });
            }
        });

        // Auto-focus on page load and after processing
        window.addEventListener('load', function() {
            if (orderIdInput) {
                orderIdInput.focus();
            }
            loadScannedOrders();
        });

        function processOrder(orderId) {
            if (isProcessing) return;
            isProcessing = true;

            const resultDiv = document.getElementById('scanResult');
            resultDiv.innerHTML = '<div class="alert alert-info">Processing order ' + orderId + '...</div>';

            fetch('{{ route('order.scanReturnReceived') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    isProcessing = false;
                    if (data.success) {
                        resultDiv.innerHTML = '<div class="alert alert-success">' +
                            '<strong>Success!</strong><br>' +
                            data.message + '<br>' +
                            'Order ID: ' + orderId + '<br>' +
                            'Order Status: Return<br>' +
                            (data.consignment_id ? 'Consignment ID: ' + data.consignment_id + '<br>' : '') +
                            '</div>';
                        orderIdInput.value = '';
                        orderIdInput.focus();
                        // Reload the table
                        loadScannedOrders();
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger">' +
                            '<strong>Error!</strong><br>' +
                            data.message +
                            '</div>';
                        orderIdInput.value = '';
                        orderIdInput.focus();
                    }
                })
                .catch(error => {
                    isProcessing = false;
                    resultDiv.innerHTML = '<div class="alert alert-danger">' +
                        '<strong>Error!</strong><br>' +
                        'An error occurred while processing the order.' +
                        '</div>';
                    console.error('Error:', error);
                    orderIdInput.value = '';
                    orderIdInput.focus();
                });
        }

        function loadScannedOrders() {
            const date = filterDateInput.value;
            const tbody = document.getElementById('ordersTableBody');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';

            fetch('{{ route('order.getScannedOrders') }}?date=' + date + '&type=return')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayOrders(data.orders);
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">' + data.message +
                            '</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="text-center text-danger">Error loading orders</td></tr>';
                });
        }

        function displayOrders(orders) {
            const tbody = document.getElementById('ordersTableBody');
            const totalCodElement = document.getElementById('totalCod');
            
            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No orders scanned today</td></tr>';
                totalCodElement.textContent = '0.00';
                return;
            }

            let html = '';
            let totalCod = 0;
            orders.forEach((order, index) => {
                const cod = parseFloat(order.cod) || 0;
                totalCod += cod;
                html += '<tr>' +
                    '<td>' + (orders.length - index) + '</td>' +
                    '<td>INV' + order.id + '</td>' +
                    '<td>' +
                    '<strong>Name</strong> ' + (order.customer_name || 'N/A') + '<br>' +
                    '<strong>Phone</strong> ' + (order.customer_phone || 'N/A') + '<br>' +
                    '<strong>Address</strong> ' + (order.customer_address || 'N/A') +
                    '</td>' +
                    '<td>' + Math.floor(cod) + '</td>' +
                    '</tr>';
            });
            tbody.innerHTML = html;
            totalCodElement.textContent = Math.floor(totalCod);
        }

        // printTable removed; printing handled via server-rendered view
    </script>
@endsection
