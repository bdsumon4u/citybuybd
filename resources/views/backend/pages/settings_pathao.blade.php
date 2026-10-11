@extends('backend.layout.template')
@section('body-content')


<div class="br-pagebody">
    
    
    @foreach($allSettings as $setting)
 

        
    <div class="row d-flex">
        
        <div class="col-md-5 card bd-0 pd-15 overflow-hidden mr-5">
            <h4 class=text-center> Pathao API</h4>
                <form action="{{ route('settings.pathaoUpdate', $setting->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Store ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_store_id" value="{{ $setting->pathao_store_id}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Client ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_client_id" value="{{ $setting->pathao_client_id}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Client Secret</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_client_secret" value="{{ $setting->pathao_client_secret}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Email</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_email" value="{{ $setting->pathao_email}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Password</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_password" value="{{ $setting->pathao_password}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="pathao_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($setting->pathao_status==1)selected @endif>Active</option>
                                        <option value="0" @if($setting->pathao_status==0)selected @endif>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <input type="submit" name="pathaoapi" value="Update" class="btn btn-teal btn-block mg-b-10">
                    </div>
            </form>
        </div>
   
        
        <div class="col-md-5 card bd-0 pd-15 overflow-hidden">
            <h4 class=text-center> Staedfast API</h4>
                <form action="{{ route('settings.steadfastUpdate', $setting->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Api Key</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="steadfast_apikey" value="{{ $setting->steadfast_apikey}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Secret Key</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="steadfast_secretkey" value="{{ $setting->steadfast_secretkey}}" class="form-control">
                                </div>
                            </div>
                           
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="steadfast_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($setting->steadfast_status==1)selected @endif>Active</option>
                                        <option value="0" @if($setting->steadfast_status==0)selected @endif>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <input type="submit" name="steadfastapi" value="Update" class="btn btn-teal btn-block mg-b-10">
                    </div>
            </form>
        </div>
        
    </div>        
        
        
        <br>
    <div class="row d-flex">
        
   
   
        
        <div class="col-md-5 card bd-0 pd-15 overflow-hidden mr-5">
            <h4 class=text-center> RedX API</h4>
                <form action="{{ route('settings.redxUpdate', $setting->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Api Access Token</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="redx_token" value="{{ $setting->redx_token}}" class="form-control">
                                </div>
                            </div>
                           
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="redx_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($setting->redx_status==1)selected @endif>Active</option>
                                        <option value="0" @if($setting->redx_status==0)selected @endif>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <input type="submit" name="redxapi" value="Update" class="btn btn-teal btn-block mg-b-10">
                    </div>
            </form>
        </div>

        <div class="col-md-5 card bd-0 pd-15 overflow-hidden">
            <h4 class="text-center"> Bahok API</h4>
            <form action="{{ route('settings.bahokUpdate', $setting->id)}}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">Username</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="text" name="bahok_username" value="{{ $setting->bahok_username }}" class="form-control" placeholder="Bahok Merchant Username">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">Password</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="password" name="bahok_password" value="{{ $setting->bahok_password }}" class="form-control" placeholder="Bahok Password">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">Client ID</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="text" name="bahok_client_id" value="{{ $setting->bahok_client_id }}" class="form-control" placeholder="Default: bK8mepphokeMy">
                                <small class="text-primary d-block mt-1"><strong>Note:</strong> This Client ID is also used as the <strong>Webhook Auth Token</strong> in Bahok portal.</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">Client Secret</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <input type="text" name="bahok_client_secret" value="{{ $setting->bahok_client_secret }}" class="form-control" placeholder="Default: ZK21kv1S1yhHD5hjs1u7ltIjkXPFDPFTIonmRF7s">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">API Status</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <select name="bahok_status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="1" @if($setting->bahok_status==1)selected @endif>Active</option>
                                    <option value="0" @if($setting->bahok_status==0)selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label class="col-sm-3 form-control-label">Webhook URL</label>
                            <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                <div class="input-group">
                                    <input type="text" id="bahok_webhook_url" value="{{ url('/bahok-status-update') }}" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('bahok_webhook_url').value); alert('Webhook URL copied!');">Copy</button>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Paste this in <strong>Bahok Merchant &gt; Developer API &gt; Webhook URL</strong></small>
                                <small class="text-muted d-block">For <strong>Webhook Auth Token</strong> in Bahok, paste your <strong>Client ID</strong> (e.g. <code>{{ $setting->bahok_client_id ?: 'bK8mepphokeMy' }}</code>)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-5">
                    <input type="submit" name="bahokapi" value="Update" class="btn btn-teal btn-block mg-b-10">
                </div>
            </form>
        </div>
        
    </div>        
        
   
    @endforeach
    
    
    
</div>
@endsection