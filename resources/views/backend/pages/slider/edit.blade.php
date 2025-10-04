@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('slider.update', $slider->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">

                           <div class="form-group">
                                <label >slider Image</label>

                                <input type="file" class="form-control-file" required="required" name="name">
                               <div class="mt-2">
                                   <img src="{{ asset('backend/img/sliders/'.$slider->name)  }}" width="100">
                               </div>
                           </div>



                          <div class="form-group">
                                <label>Status</label>
                                <select name="status"  class="form-control">

                                    <option value="1"@if($slider->status==1)selected @endif>Published</option>
                                    <option value="0"@if($slider->status==0)selected @endif>Unpublished</option>
                                </select>
                             </div>
                            <div class="form-group">
                                <input type="submit" name="addslider" value="Update slider" class="btn btn-teal btn-block mg-b-10">
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
