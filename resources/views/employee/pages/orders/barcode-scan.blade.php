@extends('employee.layout.template')
@section('body-content')

<div class="br-pagebody">
    <div class="br-section-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-2 card">
                    <div class="card-body">
                        <h4 class="tx-gray-800">Barcode Scanner</h4>
                        <p class="mb-0">Scan order barcode using physical scanner to book to courier and update status from Pending Delivery to Total Courier</p>
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
                                <input type="text" class="form-control form-control-lg" id="order_id" name="order_id" placeholder="Scan barcode or enter Order ID" autofocus>
                                <small class="form-text text-muted">The input field is auto-focused. Scan barcode using your physical scanner.</small>
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
    </div>
</div>

<script>
    const orderIdInput = document.getElementById('order_id');
    let isProcessing = false;

    // Auto-focus on page load and after processing
    window.addEventListener('load', function() {
        orderIdInput.focus();
    });

    // Handle form submission (Enter key from barcode scanner)
    document.getElementById('scanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const orderId = orderIdInput.value.trim();
        if (orderId && !isProcessing) {
            processOrder(orderId);
        }
    });

    // Also handle input event for immediate processing (some scanners don't send Enter)
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

    function processOrder(orderId) {
        if (isProcessing) return;
        isProcessing = true;

        const resultDiv = document.getElementById('scanResult');
        resultDiv.innerHTML = '<div class="alert alert-info">Processing order ' + orderId + '...</div>';

        fetch('{{ route("employee.order.scanOrder") }}', {
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
                    'Order Status: Total Courier<br>' +
                    (data.consignment_id ? 'Consignment ID: ' + data.consignment_id + '<br>' : '') +
                    '</div>';
                orderIdInput.value = '';
                orderIdInput.focus();
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
</script>

@endsection

