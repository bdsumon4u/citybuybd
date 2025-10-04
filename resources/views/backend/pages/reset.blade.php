@extends('backend.layout.template')
@section('body-content')

       <div class="br-pagebody">
       	<div class="br-section-wrapper" style="padding: 10px !important;">
       	<form action="{{route('admin.r_store')}}" method="POST">
       		@csrf

       		<div class="form-group">
       			<label>Old Pass</label>
       			<input type="password" name="old_pass" class="form-control">
       		</div>
            <div class="form-group">
       			<label>New Pass</label>
       			<input type="password" name="new_pass" class="form-control">
       		</div>
       		<input type="submit" class="btn btn-info" value="update">


       	</form>
       </div>
       	</div>

@endsection
