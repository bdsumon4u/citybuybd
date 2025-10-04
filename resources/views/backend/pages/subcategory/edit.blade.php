@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('subcategory.update', $subcategory->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">


                           <div class="form-group">
                                <label >subcategory Title</label>
                                <input type="text" name="title" class="form-control" autocomplete="off" required="required" value="{{ $subcategory->title}}" placeholder="Enter subcategory Name">
                            </div>
                            
                            <div class="form-group">
                                        <label class="col-sm-3 form-control-label">Sub Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="category_id" required="required" class="form-control">
                                                <option value="">Please select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" @if($category->id == $subcategory->category_id) selected @endif>{{$category->title}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" @if($subcategory->status==1) selected @endif >Active</option>
                                    <option value="0" @if($subcategory->status==0) selected @endif >Inactive</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <input type="submit" name="Updatesubcategory" value="Save Changes" class="btn btn-teal btn-block mg-b-10">
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
