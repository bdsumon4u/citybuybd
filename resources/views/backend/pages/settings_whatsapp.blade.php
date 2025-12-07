@extends('backend.layout.template')
@section('body-content')

<style>
    .status-card {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        transition: box-shadow 0.2s;
    }
    .status-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .template-variables code {
        background-color: #f5f5f5;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.9em;
        color: #d63384;
    }
</style>

<div class="br-pagebody">
    @foreach($allSettings as $setting)

    <div class="row">
        <div class="col-lg-12">
            <div class="overflow-hidden card bd-0 pd-15">
                <h4 class="mb-4 text-center">WhatsApp Settings</h4>
                <form action="{{ route('settings.whatsappUpdate', $setting->id)}}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">API Configuration</h5>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">From Phone Number ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="whatsapp_from_phone_number_id" value="{{ $setting->whatsapp_from_phone_number_id ?? '' }}" class="form-control" placeholder="Enter WhatsApp From Phone Number ID">
                                </div>
                            </div>

                            <div class="mt-3 row">
                                <label class="col-sm-3 form-control-label">WhatsApp Token</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <textarea name="whatsapp_token" class="form-control" rows="3" placeholder="Enter WhatsApp Token">{{ $setting->whatsapp_token ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="mb-3">Order Status Notifications</h5>
                            <p class="mb-4 text-muted">Configure WhatsApp notifications for each order status. Template name defaults to the status name in snake_case if left empty.</p>

                            <div class="row">
                                @foreach(\App\Models\Order::STATUS_MAP as $key => $status)
                                <div class="mb-4 col-md-4">
                                    <div class="card bd-0 pd-15 status-card" style="height: 100%;">
                                        <h6 class="mb-3 font-weight-bold text-primary">{{ str($status)->title() }}</h6>

                                        <div class="mb-3">
                                            <label class="d-flex align-items-center">
                                                <input type="checkbox"
                                                       name="whatsapp_notification_enabled_{{ $status }}"
                                                       value="1"
                                                       {{ $setting->{'whatsapp_notification_enabled_' . $status} == 1 ? 'checked' : '' }}
                                                       class="mr-2">
                                                <span>Enable Notification</span>
                                            </label>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-control-label">Template Name</label>
                                            <input type="text"
                                                   name="whatsapp_template_name_{{ $status }}"
                                                   value="{{ $setting->{'whatsapp_template_name_' . $status} ?? $status }}"
                                                   class="form-control"
                                                   placeholder="Default: {{ $status }}">
                                        </div>

                                        <div class="template-variables">
                                            <label class="mb-2 form-control-label">Template Variables</label>
                                            <div class="small text-muted" style="font-size: 0.85rem; line-height: 1.6;">
                                                @foreach(\App\Models\Order::getTemplateVariables(new \App\Models\Order(['status' => $key])) as $variable => $value)
                                                <div><code><?= '{{'.$loop->index.'}}' ?></code>: {{$variable}}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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

