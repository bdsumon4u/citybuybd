@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('landing.update', $landing->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-sm-3 form-control-label">Product Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="product_id" required="required" class="form-control select2">
                                                <option value="">Please select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{$product->id}}" @if($landing->product_id == $product->id) selected @endif> {{$product->name}} </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Page Title*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="heading" class="form-control" autocomplete="off" required="required" placeholder="" value="{{$landing->heading}}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Quality Assurance </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="subheading" class="form-control" autocomplete="off" placeholder="" value="{{$landing->subheading}}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Video Url (Embedded Code) </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file" name="video" class="form-control" autocomplete="off"  placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Product Overview </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="heading_middle" class="form-control" autocomplete="off"  placeholder="" value="{{$landing->heading_middle}}">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Slider Top Text </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="slider_title" class="form-control" autocomplete="off"  placeholder="" value="{{$landing->slider_title}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                   <div class="row mt-3 d-none">
                                   <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <input type="number" name="old_price" class="form-control" autocomplete="off" placeholder="" value="{{$landing->old_price}}">
                                    </div>
                                    </div>
                                    <div class="row mt-3 d-none">
                                        <label class="col-sm-3 form-control-label">Home Delivery </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="home_delivery" class="form-control" autocomplete="off"  placeholder="" value="{{$landing->home_delivery}}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Gallery Title</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="gallery_title" class="form-control" autocomplete="off" placeholder="" value="{{$landing->gallery_title}}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Gallery Image</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            @if($landing->slider)

                                                @foreach (json_decode($landing->slider) as $sl)

                                                    <img src="{{ asset('backend/img/landing/'.$sl)  }}" width="50">

                                                @endforeach
                                            @endif

                                            <input type="file" name="gallery_images[]"  multiple class="form-control-file">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Testimonial Title</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="testimonial_title" class="form-control" autocomplete="off" placeholder="" value="{{$landing->testimonial_title}}">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Testimonial Image</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            @if($landing->testimonials)

                                                @foreach (json_decode($landing->testimonials) as $test)

                                                    <img src="{{ asset('backend/img/landing/'.$test)  }}" width="50">

                                                @endforeach
                                            @endif

                                            <input type="file" name="testimonial_images[]"  multiple class="form-control-file">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Status</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="status"  class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1"@if($landing->status==1)selected @endif>Published</option>
                                                <option value="0"@if($landing->status==0)selected @endif>Unpublished</option>
                                            </select>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label>Feature </label>
                                <textarea name="bullet" class="form-control summernote " rows="4">{{$landing->bullet}}</textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="submit" name="addproduct" value="Update product" class="btn btn-teal btn-block mg-b-10">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection







