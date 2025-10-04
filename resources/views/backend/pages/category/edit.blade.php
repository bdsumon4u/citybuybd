@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('category.update', $category->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">


                           <div class="form-group">
                                <label >Category Title</label>
                                <input type="text" name="title" class="form-control" autocomplete="off" required="required" value="{{ $category->title}}" placeholder="Enter Category Name">
                            </div>
                            
                            <!--<div class="form-group">-->
                            <!--    <label >Serial</label>-->
                            <!--    <input type="text" name="serial" class="form-control" autocomplete="off" value="{{ $category->serial}}" placeholder="Enter Category serial">-->
                            <!--</div>-->
                            
                            <div class="form-group">
                                <label >Image* </label>
                                <div>
                                    <img src="{{ asset('backend/img/category/'.$category->image)  }}" width="50">
                                    <input type="file" @if($category->image == NULL) required="required" @endif  name="image" class="form-control-file">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">

                                    <option value="1" @if($category->status==1) selected @endif >Active</option>
                                    <option value="0" @if($category->status==0) selected @endif >Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="UpdateCategory" value="Save Changes" class="btn btn-teal btn-block mg-b-10">
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
