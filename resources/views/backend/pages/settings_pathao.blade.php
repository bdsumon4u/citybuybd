@extends('backend.layout.template')
@section('body-content')


<div class="br-pagebody">
    
    
    @foreach($settings as $settings)
 

        
    <div class="row d-flex">
        
        <div class="col-md-5 card bd-0 pd-15 overflow-hidden mr-5">
            <h4 class=text-center> Pathao API</h4>
                <form action="{{ route('settings.pathaoUpdate', $settings->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Store ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_store_id" value="{{ $settings->pathao_store_id}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Client ID</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_client_id" value="{{ $settings->pathao_client_id}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Client Secret</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_client_secret" value="{{ $settings->pathao_client_secret}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Email</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_email" value="{{ $settings->pathao_email}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">Password</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="pathao_password" value="{{ $settings->pathao_password}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="pathao_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($settings->pathao_status==1)selected @endif>Active</option>
                                        <option value="0" @if($settings->pathao_status==0)selected @endif>Inactive</option>
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
                <form action="{{ route('settings.steadfastUpdate', $settings->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Api Key</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="steadfast_apikey" value="{{ $settings->steadfast_apikey}}" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Secret Key</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="steadfast_secretkey" value="{{ $settings->steadfast_secretkey}}" class="form-control">
                                </div>
                            </div>
                           
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="steadfast_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($settings->steadfast_status==1)selected @endif>Active</option>
                                        <option value="0" @if($settings->steadfast_status==0)selected @endif>Inactive</option>
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
        
   
   
        
        <div class="col-md-5 card bd-0 pd-15 overflow-hidden">
            <h4 class=text-center> RedX API</h4>
                <form action="{{ route('settings.redxUpdate', $settings->id)}}" enctype="multipart/form-data"  method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label"> Api Access Token</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <input type="text" name="redx_token" value="{{ $settings->redx_token}}" class="form-control">
                                </div>
                            </div>
                           
                            <div class="row mt-3">
                                <label class="col-sm-3 form-control-label">API Status</label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <select name="redx_status"  class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" @if($settings->redx_status==1)selected @endif>Active</option>
                                        <option value="0" @if($settings->redx_status==0)selected @endif>Inactive</option>
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
        
   
    @endforeach
    
    
    
</div>
@endsection