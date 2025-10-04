@extends('backend.layout.template')
@section('body-content')


    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('product.store')}}" enctype="multipart/form-data"  method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-sm-3 form-control-label">Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="category_id" id="category_id" required="required" class="form-control">
                                                <option value="">Please select Category</option>
                                                @foreach(App\Models\Category::all() as $category)

                                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Sub Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="subcategory_id" id="subcategory_id" class="form-control">
                                                <option value="{{ $subcategory->id ?? '' }}">{{ $subcategory->title ?? ''}} </option>
                                                
                                            </select>
                                        </div>
                                    </div>
                            
                             <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Child Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="childcategory_id" id="childcategory_id"  class="form-control">
                                                <option value="{{ $childcategory->id ?? ''}}">{{ $childcategory->title ?? ''}}  </option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Brand Name</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="brand_id" class="form-control">
                                                <option value="">Please select Brand</option>
                                                @foreach(App\Models\Brand::all() as $brand)

                                                    <option value="{{$brand->id}}">{{$brand->title}}</option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">product Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Enter product Name">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">SKU </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="sku" class="form-control" autocomplete="off" placeholder="Enter SKU code">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Serial </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="serial" class="form-control" autocomplete="off" placeholder="Enter serial">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Regular Price* </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="regular_price" class="form-control" autocomplete="off" required="required" placeholder="Enter regular price">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Offer Price </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="offer_price" class="form-control" autocomplete="off"  placeholder="Enter offer price">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Stock </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" name="stock" class="form-control" autocomplete="off"  placeholder="Enter Stock">
                                        </div>
                                    </div>



                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group col-12 " >
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
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Featured thumbnail* (180*180)</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file" required="" name="image" class="form-control-file">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Gallery Image (400*400)</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file" name="gallery_images[]" multiple class="form-control-file">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Video</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="file"  name="video" class="form-control-file">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Free Shipping</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="radio" name="shipping" value="1" >
                                             <label for="html">YES</label><br>
                                             <input type="radio" name="shipping" value="0" >
                                             <label for="css">NO</label><br>
                                             <input type="radio" name="shipping" value="2" checked>
                                             <label for="css">NORMAL</label><br>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Shipping Cost (Inside Dhaka) </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value='0' name="inside" class="form-control" autocomplete="off" required="required" >
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Shipping Cost (Outside Dhaka) </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value='0' name="outside" class="form-control" autocomplete="off" required="required" >
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
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Product Assign</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="assign" class="form-control select2">
                                                <option value="">Select Employee</option>
                                                @foreach(App\Models\User::where('role',3)->get() as $user)

                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label>Description </label>
                                <textarea name="description" class="form-control  ckeditor  " rows="4"></textarea>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="submit" name="addproduct" value="Add New product" class="btn btn-teal btn-block mg-b-10">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection





