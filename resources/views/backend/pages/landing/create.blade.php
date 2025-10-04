@extends('backend.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('landing.store')}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-sm-3 form-control-label">Product Name**</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="product_id" required="required" class="form-control select2">
                                                <option value="">Please select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Page Title**</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="heading" class="form-control" autocomplete="off" required="required" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Quality Assurance </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="subheading" class="form-control" autocomplete="off" placeholder="">
                                        </div>
                                    </div>



                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Video Url </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file" name="video" class="form-control" autocomplete="off"  placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Product Overview </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="heading_middle" class="form-control" autocomplete="off"  placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Slider Top Text </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="slider_title" class="form-control" autocomplete="off"  placeholder="">
                                        </div>
                                    </div>






                                </div>
                                <div class="col-lg-6">
                                    <!--<div class="row mt-3 d-none">-->
                                    <!--    <label class="col-sm-3 form-control-label">Old Price** </label>-->
                                    <!--    <div class="col-sm-9 mg-t-10 mg-sm-t-0">-->
                                    <!--        <input type="number" name="old_price" class="form-control" autocomplete="off"  placeholder="" required="required">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--<div class="row mt-3 d-none">-->
                                    <!--    <label class="col-sm-3 form-control-label">New Price** </label>-->
                                    <!--    <div class="col-sm-9 mg-t-10 mg-sm-t-0">-->
                                    <!--        <input type="number" name="new_price" class="form-control" autocomplete="off"  placeholder="" required="required">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Phone Number** </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="phone" class="form-control" autocomplete="off"  placeholder="" required="required">
                                        </div>
                                    </div>
                                    <div class="row mt-3 d-none">
                                        <label class="col-sm-3 form-control-label">Home Delivery </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="home_delivery" class="form-control" autocomplete="off"  placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Sliders</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file" name="gallery_images[]" multiple class="form-control-file">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Status</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="status" class="form-control">
                                                <option value="1">Select Status</option>
                                                <option value="1">Published</option>
                                                <option value="0">Unpublished</option>
                                            </select>
                                        </div>
                                    </div>

                {{--                     <div class="form-group col-12 " >
                                        <h5 class="mb-1">Attributes</h5>
                                        <div class="form-row">
                                            @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                <div class="form-group col-md-3 col-12">
                                                    <input type="checkbox" name="atr[]" class="attribute_id" value="{{$attribute->id}}">
                                                    <label class="text-capitalize" for="">{{$attribute->name}}</label>
                                                    <div class="sub_atr">
                                                        @foreach(App\Models\Atr_item::where('atr_id',$attribute->id)->get() as $att_item)
                                                            <p class="mb-0">
                                                                <input type="checkbox" name="att_item[]" class="attribute_item" value="{{$att_item->id}}">
                                                                <label class="text-capitalize" for="">{{$att_item->name}}</label>
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div> --}}

                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label>Feature </label>
                                <textarea name="bullet" class="form-control summernote " rows="4"></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="submit" value="Add New product" class="btn btn-teal btn-block mg-b-10">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
