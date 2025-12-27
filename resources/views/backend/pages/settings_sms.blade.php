@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    @foreach($allSettings as $setting)

    <div class="row">
        <div class="col-lg-12">
            <div class="overflow-hidden card bd-0 pd-15">
                <h4 class="mb-4 text-center">SMS Settings</h4>
                <form action="{{ route('settings.smsUpdate', $setting->id)}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">API Configuration</h5>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">API URL</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="sms_api_url" value="{{ $setting->sms_api_url ?? '' }}" class="form-control" placeholder="Enter SMS API URL (optional, depends on provider)">
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">API Key</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="sms_api_key" value="{{ $setting->sms_api_key ?? '' }}" class="form-control" placeholder="Enter SMS API Key">
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">API Secret / Client ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="sms_api_secret" value="{{ $setting->sms_api_secret ?? '' }}" class="form-control" placeholder="Enter SMS API Secret (if required)">
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">Sender ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="sms_sender_id" value="{{ $setting->sms_sender_id ?? '' }}" class="form-control" placeholder="Enter Sender ID (if required)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">Order Status Notifications</h5>
                            <p class="mb-4 text-muted">Configure SMS notifications for each order status. You can use variables like {name}, {order_id}, {amount}.</p>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order Status</th>
                                            <th>Enable Notification</th>
                                            <th>SMS Template Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $statuses = [
                                                'processing' => 'Processing',
                                                'pending_delivery' => 'Courier Entry',
                                                'on_hold' => 'On Hold',
                                                'cancel' => 'Cancel',
                                                'completed' => 'Completed',
                                                'pending_payment' => 'Pending Payment',
                                                'on_delivery' => 'On Delivery',
                                                'no_response1' => 'No Response 1',
                                                'no_response2' => 'Printed Invoice',
                                                'courier_hold' => 'Courier Hold',
                                                'order_return' => 'Return'
                                            ];
                                        @endphp
                                        @foreach($statuses as $key => $label)
                                        <tr>
                                            <td><strong>{{ $label }}</strong></td>
                                            <td>
                                                <input type="checkbox" name="sms_notification_enabled_{{ $key }}" value="1" {{ $setting->{'sms_notification_enabled_' . $key} == 1 ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <textarea name="sms_template_{{ $key }}" class="form-control" rows="3" placeholder="Dear {name},&#10;Your order #{order_id} is {{ strtolower($label) }}.&#10;Product Details:&#10;{product_details}&#10;Total amount: {amount}">{{ $setting->{'sms_template_' . $key} ?? '' }}</textarea>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 form-group">
                        <input type="submit" name="sms_settings" value="Update SMS Settings" class="btn btn-teal btn-block mg-b-10">
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endforeach
</div>

@endsection

