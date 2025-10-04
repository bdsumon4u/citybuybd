@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('brand.update', $brand->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">

                           <div class="form-group">
                                <label >Brand Title</label>
                                <input type="text" name="title" class="form-control" autocomplete="off" required="required" value="{{ $brand->title}}" placeholder="Enter brand Name">
                            </div>


                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">

                                    <option value="1" @if($brand->status==1) selected @endif >Active</option>
                                    <option value="0" @if($brand->status==0) selected @endif >Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="UpdateBrand" value="Save Changes" class="btn btn-teal btn-block mg-b-10">
                             </div>

                                </div>

                            </div>







                        </form>
                        </div>
                    </div>

                </div>
            </div>
          </div>

@endsection
