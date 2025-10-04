@extends('backend.layout.template')
@section('body-content')

<style>
    .cke_notifications_area{
        display: none;
    }
</style>

    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('product.update', $product->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-sm-3 form-control-label">Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="category_id" id="category_id" required="required" class="form-control">
                                                <option value="">Please select Category</option>
                                                @foreach(App\Models\Category::all() as $category)
                                                    <option value="{{$category->id}}" @if($category->id == $product->category_id) selected @endif>{{$category->title}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Sub Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="subcategory_id" id="subcategory_id"  class="form-control">
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
                                        <label class="col-sm-3 form-control-label">Brand Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="brand_id" class="form-control">
                                                <option value="">Please select Brand</option>
                                                @foreach(App\Models\Brand::all() as $brand)
                                                    <option value="{{$brand->id}}" @if($brand->id == $product->brand_id) selected @endif>{{$brand->title}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">product Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="name" class="form-control" autocomplete="off" required="required" value="{{$product->name}}" placeholder="Enter product Name">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">SKU </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="sku" value="{{$product->sku}}" class="form-control" autocomplete="off" placeholder="Enter SKU code">
                                        </div>
                                    </div>
                                    
                                     <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Serial </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="serial" value="{{$product->serial}}" class="form-control" autocomplete="off" placeholder="Enter serial">
                                        </div>
                                    </div>


                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Regular Price* </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{$product->regular_price}}" name="regular_price" class="form-control" autocomplete="off" required="required" placeholder="Enter regular price">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Offer Price </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{$product->offer_price}}" name="offer_price" class="form-control" autocomplete="off"  placeholder="Enter offer price">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Stock </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{$product->stock}}"  name="stock" class="form-control" autocomplete="off"  placeholder="Enter Stock">
                                        </div>
                                    </div>



                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group col-12 " >
                                        <h5 class="mb-1">Attributes</h5>
                                        <div class="form-row">
                                            @foreach(App\Models\ProductAttribute::all() as $attribute)
                                                <div class="form-group col-md-3 col-12">
                                                    {{-- Decode the product's attribute array --}}
                                                    @php
                                                        $selectedAttributes = json_decode($product->atr, true);
                                                        $selectedItems = json_decode($product->atr_item, true);
                                                    @endphp

                                                    {{-- Check if the attribute is selected --}}
                                                    <input type="checkbox" name="atr[]" class="attribute_id" 
                                                        @if(is_array($selectedAttributes) && in_array($attribute->id, $selectedAttributes)) checked @endif
                                                        value="{{$attribute->id}}">
                                                    <label class="text-capitalize" for="">{{$attribute->name}}</label>

                                                    <div>
                                                        {{-- Loop through attribute items --}}
                                                        @foreach(App\Models\Atr_item::where('atr_id', $attribute->id)->get() as $att_item)
                                                            <p class="mb-0">
                                                                {{-- Check if the attribute item is selected --}}
                                                                <input type="checkbox" 
                                                                    @if(is_array($selectedItems) && in_array($att_item->id, $selectedItems)) checked @endif
                                                                    name="att_item[]" class="attribute_item" value="{{$att_item->id}}">
                                                                <label class="text-capitalize" for="">{{$att_item->name}}</label>
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Featured thumbnail* </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <img src="{{ asset('backend/img/products/'.$product->image)  }}" width="50">
                                            <input type="file" @if($product->image == NULL) required="required" @endif  name="image" class="form-control-file">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Gallery Image</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            @if($product->gallery_images)
                                                @foreach (json_decode($product->gallery_images) as $area)
                                                    <img src="{{ asset('backend/img/products/'.$area)  }}" width="50">
                                                @endforeach
                                            @endif
                                            <input type="file" name="gallery_images[]"  multiple class="form-control-file">
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Video</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            @if(!is_null($product->video))
                                     <video width="350" controls>
                                          <source src="{{ asset('backend/img/products/video/'.$product->video)  }}" type="video/mp4">
                                    </video>
                                @endif
                                            <input type="file" name="video" class="form-control-file" value={{ $product->video }}>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Status</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="status"  class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="1"@if($product->status==1)selected @endif>Published</option>
                                                <option value="0"@if($product->status==0)selected @endif>Unpublished</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                              
                                    
                                     <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Free Shipping</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="radio" name="shipping" value="1" {{ ($product->shipping=="1")? "checked" : "" }}>
                                             <label for="html">YES</label><br>
                                             <input type="radio" name="shipping" value="0" {{ ($product->shipping=="0")? "checked" : "" }}>
                                             <label for="css">NO</label><br>
                                             <input type="radio" name="shipping" value="2" {{ ($product->shipping=="2")? "checked" : "" }}>
                                             <label for="css">NORMAL</label><br>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Shipping Cost (Inside Dhaka) </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{$product->inside}}" name="inside" class="form-control" autocomplete="off" required="required" >
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Shipping Cost (Outside Dhaka) </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{$product->outside}}" name="outside" class="form-control" autocomplete="off" required="required" >
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Product Assign</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="assign" class="form-control">
                                                <option value="">Select Employee</option>
                                                @foreach(App\Models\User::where('role',3)->get() as $user)
                                                    <option value="{{$user->id}}"@if($user->id==$product->assign)selected @endif>{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label>Description </label>
                                <textarea name="description" class="form-control summernote  ckeditor" rows="4">{!!$product->description!!}</textarea>
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







