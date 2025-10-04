@extends('backend.layout.template')
@section('body-content')
 <div class="br-pagetitle">
        
        
      </div>
            <div class="br-pagebody">
            <div class="br-section-wrapper">
                 

            	<div class="row">
            		<div class="col-lg-12">
            			<div class="card bd-0 overflow-hidden">
                        <form action="{{ route('slider.store')}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <h6 class="br-section-label">Create new slider</h6>

                           <div class="form-group">
                                <label >slider Image[min. 1445x365px] </label>
                                <input type="file" class="form-control-file" required="required" name="name">
                            </div>

                           

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Select Primary slider</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>  
                            <div class="form-group">
                                <input type="submit" name="addslider" value="Add New slider" class="btn btn-teal btn-block mg-b-10">
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