@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">

            @foreach ($allSettings as $setting)
                <div class="row">
                    <div class="col-lg-12">

                        <div class="overflow-hidden card bd-0 pd-15">
                            <form action="{{ route('settings.update', $setting->id) }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <label class="col-sm-3 form-control-label">Website Address</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="address" class="form-control">{{ $setting->address }}</textarea>
                                            </div>

                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Phone Header</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="phone" value="{{ $setting->phone }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row d-none">
                                            <label class="col-sm-3 form-control-label">Website Phone Navigation</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="phone_two" value="{{ $setting->phone_two }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row d-none">
                                            <label class="col-sm-3 form-control-label">Website Phone Whatsapp</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="phone_three" value="{{ $setting->phone_three }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="mt-3 row d-none">
                                            <label class="col-sm-3 form-control-label">Dial-up Number</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="dial_up" value="{{ $setting->dial_up }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">WhatsApp Number</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="whatsapp_number"
                                                    value="{{ $setting->whatsapp_number }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Messenger Username</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="messenger_username"
                                                    value="{{ $setting->messenger_username }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">IMO Number</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="imo_number" value="{{ $setting->imo_number }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <!--  -->
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Email</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="email" value="{{ $setting->email }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Email 2</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="email_two" value="{{ $setting->email_two }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Facebook Link</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="fb_link" value="{{ $setting->fb_link }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Twitter Link</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="twitter_link"
                                                    value="{{ $setting->twitter_link }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Instagram Link</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="instagram_link"
                                                    value="{{ $setting->instagram_link }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Youtube Link</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="youtube_link"
                                                    value="{{ $setting->youtube_link }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Order Confirm Message</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="yt_link" value="{{ $setting->yt_link }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Name</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="insta_link"
                                                    value="{{ $setting->insta_link }}" class="form-control">
                                            </div>
                                        </div>


                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Contact Phone +</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <select name="contact_phone_plus" class="form-control">
                                                    <option value="whatsapp"
                                                        {{ $setting->contact_phone_plus == 'whatsapp' ? 'selected' : '' }}>
                                                        WhatsApp</option>
                                                    <option value="messenger"
                                                        {{ $setting->contact_phone_plus == 'messenger' ? 'selected' : '' }}>
                                                        Messenger</option>
                                                    <option value="both"
                                                        {{ $setting->contact_phone_plus == 'both' ? 'selected' : '' }}>
                                                        Both</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Copyright Text</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="copyright" class="form-control">{{ $setting->copyright }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Header Logo</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="file" class="form-control-file" name="logo">
                                                @if (!is_null($setting->logo))
                                                    <img src="{{ asset('backend/img/' . $setting->logo) }}"
                                                        width="50">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Favicon</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="file" class="form-control-file" name="favicon">
                                                @if (!is_null($setting->favicon))
                                                    <img src="{{ asset('backend/img/' . $setting->favicon) }}"
                                                        width="50">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Currency Sign</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="currency" value="{{ $setting->currency }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Website Color</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="website_color"
                                                    value="{{ $setting->website_color }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Marque Status</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <select name="marque_status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1"
                                                        @if ($setting->marque_status == 1) selected @endif>Active</option>
                                                    <option value="0"
                                                        @if ($setting->marque_status == 0) selected @endif>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">SMS Status</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <select name="sms_status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="1"
                                                        @if ($setting->sms_status == 1) selected @endif>Active</option>
                                                    <option value="0"
                                                        @if ($setting->sms_status == 0) selected @endif>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Marque Text</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="marque_text"
                                                    value="{{ $setting->marque_text }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Bkash Merchant Number</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <input type="text" name="bkash" value="{{ $setting->bkash }}"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Facebook Pixel Code</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="fb_pixel" class="form-control">{{ $setting->fb_pixel }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">CourierRank Token</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="qc_token" class="form-control">{{ $setting->qc_token }}</textarea>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">Number Block</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="number_block" class="form-control">{{ $setting->number_block }}</textarea>
                                            </div>
                                        </div>
                                        <div class="mt-3 row">
                                            <label class="col-sm-3 form-control-label">IP Block</label>
                                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                                <textarea name="ip_block" class="form-control">{{ $setting->ip_block }}</textarea>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <div class="col-md-6">
                                                <label class="form-control-label">Orders Per Hour (per IP)</label>
                                                <input type="number" name="orders_per_hour_limit" min="0"
                                                    class="form-control" placeholder="0 = unlimited"
                                                    value="{{ $setting->orders_per_hour_limit }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-control-label">Orders Per Day (per IP)</label>
                                                <input type="number" name="orders_per_day_limit" min="0"
                                                    class="form-control" placeholder="0 = unlimited"
                                                    value="{{ $setting->orders_per_day_limit }}">
                                            </div>
                                        </div>





                                    </div>
                                </div>
                                <div class="mt-5 form-group">
                                    <input type="submit" name="addShipping" value="Update"
                                        class="btn btn-teal btn-block mg-b-10">
                                </div>
            @endforeach

        </div>







        </form>
    </div>
    </div>

    </div>
    </div>
    </div>
@endsection
