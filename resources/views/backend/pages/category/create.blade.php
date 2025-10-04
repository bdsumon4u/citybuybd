@extends('backend.layout.template')
@section('body-content')

            <div class="br-pagebody">
            <div class="br-section-wrapper">


            	<div class="row">
            		<div class="col-lg-12">
            			<div class="card bd-0 pd-10 overflow-hidden">
                        <form id="SubmitForm">

                            <div class="row justify-content-center">
                                <div class="col-lg-6">

                           <div class="form-group">
                                <label >Category Title</label>
                                <input type="text" id="title" class="form-control" autocomplete="off" required="required" placeholder="Enter Category Name">
                            </div>
                            
                            <!-- <div class="form-group">-->
                            <!--    <label >Serial</label>-->
                            <!--    <input type="text" name="serial" class="form-control" autocomplete="off"  placeholder="Enter Category serial">-->
                            <!--</div>-->

                            <div class="form-group">
                                <label >Image* </label>
                                <div>
                                    <input type="file" required="" name="image" class="form-control-file">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select id="status" class="form-control">

                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" id="addCategory" value="Add New Category" class="btn btn-teal btn-block mg-b-10">
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
