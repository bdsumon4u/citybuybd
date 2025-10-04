@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">

                @foreach($settings as $settings)


                <div class="row">
                    <div class="col-lg-12">

                        <div class="card bd-0 pd-15 overflow-hidden">
                        <form action="{{ route('settings.update', $settings->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                           <div class="row">
                               <div class="col-lg-6">
                                     <div class="row">
                                <label class="col-sm-3 form-control-label">Website Address</label>
                                         <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                             <textarea name="address" class="form-control">{{ $settings->address}}</textarea>
                                         </div>

                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Phone Header</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="phone" value="{{ $settings->phone}}" class="form-control"></div>
                            </div>
                             <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Phone Navigation</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                     <input type="text" name="phone_two" value="{{ $settings->phone_two}}" class="form-control"></div>
                            </div>
                             <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Phone Whatsapp</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                     <input type="text" name="phone_three" value="{{ $settings->phone_three}}" class="form-control"></div>
                            </div>
                            <!--  -->
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Dial-up Number</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="dial_up" value="{{ $settings->dial_up }}" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">WhatsApp Number</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="whatsapp_number" value="{{ $settings->whatsapp_number }}" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Messenger Username</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="messenger_username" value="{{ $settings->messenger_username }}" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">IMO Number</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="imo_number" value="{{ $settings->imo_number }}" class="form-control">
                                </div>
                            </div>

                            <!--  -->
                             <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Email</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                     <input type="text" name="email" value="{{ $settings->email}}" class="form-control"></div>
                            </div>
                             <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Email 2</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                     <input type="text" name="email_two" value="{{ $settings->email_two}}" class="form-control"></div>
                            </div>
                             <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Facebook Link</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                     <input type="text" name="fb_link" value="{{ $settings->fb_link}}" class="form-control"></div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Footer Facebook Link</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="twitter_link" value="{{ $settings->twitter_link}}" class="form-control"></div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Order Confirm Message</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="yt_link" value="{{ $settings->yt_link}}" class="form-control"></div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Name</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="insta_link" value="{{ $settings->insta_link}}" class="form-control"></div>
                            </div>



                               </div>
                          <div class="col-lg-6">
                             <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Website Copyright Text</label>
                                 <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <textarea name="copyright" class="form-control">{{ $settings->copyright}}</textarea>
                                 </div>
                              </div>
                               <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Website Header Logo</label>
                                   <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <input type="file" class="form-control-file" name="logo">
                                   @if(!is_null($settings->logo))
                                   <img src="{{ asset('backend/img/'.$settings->logo)  }}" width="50">
                                   @endif
                                   </div>
                              </div>

                              <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Website Favicon</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <input type="file" class="form-control-file" name="favicon">
                                   @if(!is_null($settings->favicon))
                                   <img src="{{ asset('backend/img/'.$settings->favicon)  }}" width="50">
                                   @endif
                                  </div>
                              </div>
                              <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Currency Sign</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                      <input type="text" name="currency" value="{{ $settings->currency}}" class="form-control"></div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Website Color</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                      <input type="text" name="website_color" value="{{ $settings->website_color}}" class="form-control"></div>
                            </div>

                              <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Marque Status</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                      <select name="marque_status"  class="form-control">
                                          <option value="">Select Status</option>
                                          <option value="1" @if($settings->marque_status==1)selected @endif>Active</option>
                                          <option value="0" @if($settings->marque_status==0)selected @endif>Inactive</option>
                                      </select>
                                  </div>
                              </div>
                              
                            <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">SMS Status</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                      <select name="sms_status"  class="form-control">
                                          <option value="">Select Status</option>
                                          <option value="1" @if($settings->sms_status==1)selected @endif>Active</option>
                                          <option value="0" @if($settings->sms_status==0)selected @endif>Inactive</option>
                                      </select>
                                  </div>
                              </div>

                              <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Marque Text</label>
                                  <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                      <input type="text" name="marque_text" value="{{ $settings->marque_text}}" class="form-control"></div>
                            </div>

                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Bkash Merchant Number</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="bkash" value="{{ $settings->bkash}}" class="form-control"></div>
                            </div>

                            <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Facebook Pixel Code</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <textarea name="fb_pixel" class="form-control">{{ $settings->fb_pixel}}</textarea>
                                </div>
                              </div>
                              <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">CourierRank Token</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <textarea name="qc_token" class="form-control">{{ $settings->qc_token}}</textarea>
                                </div>
                              </div>
                              
                              <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">Number Block</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <textarea name="number_block" class="form-control">{{ $settings->number_block}}</textarea>
                                </div>
                              </div>
                              <div class="row mt-3">
                                  <label class="col-sm-3 form-control-label">IP Block</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                   <textarea name="ip_block" class="form-control">{{ $settings->ip_block}}</textarea>
                                </div>
                              </div>





                               </div>
                           </div>
                            <div class="form-group mt-5">
                                <input type="submit" name="addShipping" value="Update" class="btn btn-teal btn-block mg-b-10">
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
