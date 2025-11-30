@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    @foreach($settings as $settings)

    <div class="row">
        <div class="col-lg-12">
            <div class="overflow-hidden card bd-0 pd-15">
                <h4 class="mb-4 text-center">WhatsApp Settings</h4>
                <form action="{{ route('settings.whatsappUpdate', $settings->id)}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">API Configuration</h5>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">From Phone Number ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="whatsapp_from_phone_number_id" value="{{ $settings->whatsapp_from_phone_number_id ?? '' }}" class="form-control" placeholder="Enter WhatsApp From Phone Number ID">
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">WhatsApp Token</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <textarea name="whatsapp_token" class="form-control" rows="3" placeholder="Enter WhatsApp Token">{{ $settings->whatsapp_token ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">Order Status Notifications</h5>
                            <p class="mb-4 text-muted">Configure WhatsApp notifications for each order status. Template name defaults to the status name in snake_case if left empty.</p>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order Status</th>
                                            <th>Enable Notification</th>
                                            <th>Template Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Processing</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_processing" value="1" {{ $settings->whatsapp_notification_enabled_processing == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_processing" value="{{ $settings->whatsapp_template_name_processing ?? 'processing' }}" class="form-control" placeholder="Default: processing">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pending Delivery</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_pending_delivery" value="1" {{ $settings->whatsapp_notification_enabled_pending_delivery == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_pending_delivery" value="{{ $settings->whatsapp_template_name_pending_delivery ?? 'pending_delivery' }}" class="form-control" placeholder="Default: pending_delivery">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>On Hold</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_on_hold" value="1" {{ $settings->whatsapp_notification_enabled_on_hold == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_on_hold" value="{{ $settings->whatsapp_template_name_on_hold ?? 'on_hold' }}" class="form-control" placeholder="Default: on_hold">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cancel</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_cancel" value="1" {{ $settings->whatsapp_notification_enabled_cancel == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_cancel" value="{{ $settings->whatsapp_template_name_cancel ?? 'cancel' }}" class="form-control" placeholder="Default: cancel">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Completed</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_completed" value="1" {{ $settings->whatsapp_notification_enabled_completed == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_completed" value="{{ $settings->whatsapp_template_name_completed ?? 'completed' }}" class="form-control" placeholder="Default: completed">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pending Payment</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_pending_payment" value="1" {{ $settings->whatsapp_notification_enabled_pending_payment == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_pending_payment" value="{{ $settings->whatsapp_template_name_pending_payment ?? 'pending_payment' }}" class="form-control" placeholder="Default: pending_payment">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>On Delivery</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_on_delivery" value="1" {{ $settings->whatsapp_notification_enabled_on_delivery == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_on_delivery" value="{{ $settings->whatsapp_template_name_on_delivery ?? 'on_delivery' }}" class="form-control" placeholder="Default: on_delivery">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>No Response 1</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_no_response1" value="1" {{ $settings->whatsapp_notification_enabled_no_response1 == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_no_response1" value="{{ $settings->whatsapp_template_name_no_response1 ?? 'no_response1' }}" class="form-control" placeholder="Default: no_response1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>No Response 2</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_no_response2" value="1" {{ $settings->whatsapp_notification_enabled_no_response2 == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_no_response2" value="{{ $settings->whatsapp_template_name_no_response2 ?? 'no_response2' }}" class="form-control" placeholder="Default: no_response2">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Courier Hold</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_courier_hold" value="1" {{ $settings->whatsapp_notification_enabled_courier_hold == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_courier_hold" value="{{ $settings->whatsapp_template_name_courier_hold ?? 'courier_hold' }}" class="form-control" placeholder="Default: courier_hold">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Return</strong></td>
                                            <td>
                                                <input type="checkbox" name="whatsapp_notification_enabled_order_return" value="1" {{ $settings->whatsapp_notification_enabled_order_return == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" name="whatsapp_template_name_order_return" value="{{ $settings->whatsapp_template_name_order_return ?? 'order_return' }}" class="form-control" placeholder="Default: order_return">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 form-group">
                        <input type="submit" name="whatsapp_settings" value="Update WhatsApp Settings" class="btn btn-teal btn-block mg-b-10">
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endforeach
</div>

@endsection

