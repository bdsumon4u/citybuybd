<!-- Custom SMS Modal -->
<div class="modal fade" id="customSmsModal" tabindex="-1" role="dialog" aria-labelledby="customSmsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold d-flex align-items-center" id="customSmsModalLabel">
                    <i class="fa-solid fa-comment-sms text-info mr-2"></i> Send Custom SMS
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none py-2 px-3 mb-3" id="sms_modal_error_alert"></div>
                <div class="alert alert-success d-none py-2 px-3 mb-3" id="sms_modal_success_alert"></div>

                <div class="card mb-3 border-light bg-light">
                    <div class="card-body p-2 px-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 6px;">
                            <div>
                                <span class="text-muted small">Customer:</span>
                                <strong id="sms_modal_customer_name" class="ml-1 text-dark">-</strong>
                            </div>
                            <div id="sms_modal_order_badge_wrap" style="display:none;">
                                <span class="badge badge-primary px-2 py-1">Invoice #<span id="sms_modal_order_id"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-control-label font-weight-bold small mb-1" for="sms_modal_phone">
                        Recipient Phone Number <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa-solid fa-phone text-muted"></i></span>
                        </div>
                        <input type="text" id="sms_modal_phone" class="form-control font-weight-bold" placeholder="01XXXXXXXXX" required>
                    </div>
                    <small class="form-text text-muted">Bangladeshi 11-digit mobile number</small>
                </div>

                <div class="form-group mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-control-label font-weight-bold small mb-0" for="sms_modal_message">
                            Message <span class="text-danger">*</span>
                        </label>
                        <span id="sms_encoding_badge" class="badge badge-light border text-muted">GSM (English)</span>
                    </div>
                    <textarea id="sms_modal_message" class="form-control" rows="4" placeholder="Type your SMS message here..." required></textarea>

                    <div class="d-flex justify-content-between align-items-center mt-2 text-muted small">
                        <span>Characters: <strong id="sms_char_count">0</strong> (<strong id="sms_parts_count">1</strong> SMS)</span>
                        <span id="sms_chars_remaining">160 chars left in part 1</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_submit_send_sms" class="btn btn-info btn-sm font-weight-bold px-3">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Send SMS
                </button>
            </div>
        </div>
    </div>
</div>
