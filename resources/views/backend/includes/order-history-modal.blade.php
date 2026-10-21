<!-- Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="orderHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="max-width: 95%;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light py-2 px-3">
                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                    <h5 class="modal-title font-weight-bold mb-0 text-dark d-flex align-items-center" id="orderHistoryModalLabel">
                        <i class="fa-solid fa-clock-rotate-left text-primary mr-2"></i> Customer Order History
                    </h5>
                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                        <i class="fa-solid fa-phone text-muted mr-1"></i> <span id="history_modal_phone">-</span>
                    </span>
                    <span class="badge badge-primary px-2 py-1" id="history_modal_customer_name">-</span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3" id="order_history_modal_body">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function setupOrderHistory() {
            if (typeof jQuery === 'undefined') {
                setTimeout(setupOrderHistory, 50);
                return;
            }
            var $ = jQuery;

            $(document).off('click', '.order-history-btn').on('click', '.order-history-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var btn = $(this);
                var phone = btn.data('phone') || btn.attr('data-phone') || '';
                var name = btn.data('name') || btn.attr('data-name') || '';

                if (!phone) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('No phone number found.');
                    } else {
                        alert('No phone number found.');
                    }
                    return;
                }

                $('#history_modal_phone').text(phone);
                $('#history_modal_customer_name').text(name || 'Customer');

                $('#order_history_modal_body').html(
                    '<div class="py-5 text-center text-muted">' +
                    '<div class="spinner-border text-primary mb-2" role="status" style="width: 2.5rem; height: 2.5rem;"></div>' +
                    '<div class="font-weight-bold">Loading order history for ' + phone + '...</div>' +
                    '</div>'
                );

                $('#orderHistoryModal').modal('show');

                $.ajax({
                    url: "{{ route('order.phone-history') }}",
                    type: "GET",
                    data: { phone: phone },
                    dataType: "json",
                    success: function (response) {
                        if (response.status && response.html) {
                            $('#order_history_modal_body').html(response.html);
                            if (response.customer && response.customer.name) {
                                $('#history_modal_customer_name').text(response.customer.name);
                            }
                        } else {
                            $('#order_history_modal_body').html(
                                '<div class="alert alert-warning text-center my-3">No orders found for this customer.</div>'
                            );
                        }
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to load order history.';
                        $('#order_history_modal_body').html(
                            '<div class="alert alert-danger text-center my-3"><i class="fa-solid fa-triangle-exclamation mr-1"></i> ' + msg + '</div>'
                        );
                    }
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupOrderHistory);
        } else {
            setupOrderHistory();
        }
    })();
</script>

<style>
/* Order History Button - match .send-sms-btn and .copy-phone-btn dimensions */
.order-history-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 20px !important;
    height: 19px !important;
    padding: 0 !important;
    font-size: 10px !important;
    line-height: 1 !important;
    border-radius: 4px !important;
    color: #4f46e5 !important;
    border: 1px solid #c7d2fe !important;
    background-color: #eef2ff !important;
    transition: all 0.15s ease-in-out !important;
    vertical-align: middle !important;
    cursor: pointer !important;
    margin-left: 3px !important;
}

.order-history-btn:hover {
    color: #ffffff !important;
    border-color: #4f46e5 !important;
    background-color: #4f46e5 !important;
}

.dark-theme .order-history-btn {
    background-color: rgba(99, 102, 241, 0.15) !important;
    border-color: rgba(129, 140, 248, 0.35) !important;
    color: #a5b4fc !important;
}

.dark-theme .order-history-btn:hover {
    background-color: #4f46e5 !important;
    border-color: #818cf8 !important;
    color: #ffffff !important;
}

.dark-theme .order-history-btn i {
    color: inherit !important;
}
</style>

