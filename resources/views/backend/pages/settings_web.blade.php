@extends('backend.layout.template')
@section('body-content')

<style>
    .cke_notifications_area{
        display: none;
    }
</style>

            <div class="br-pagebody">
            <div class="br-section-wrapper">

                @foreach($settings as $settings)


                <div class="row">
                    <div class="col-lg-12">

                        <div class="card bd-0 pd-15 overflow-hidden">
                        <form action="{{ route('settings.update.page', $settings->id)}}" enctype="multipart/form-data"  method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">
                                    <label class="col-sm-3 form-control-label">About Us</label>
                                    <textarea name="about_us" class="form-control ckeditor" ckeditor>{{ $settings->about_us}}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">
                                    <label class="col-sm-3 form-control-label">Delivery Policy</label>
                                    <textarea name="delivery_policy" class="form-control ckeditor">{{ $settings->delivery_policy}}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">
                                    <label class="col-sm-3 form-control-label">Return Policy</label>
                                    <textarea name="return_policy" class="form-control ckeditor">{{ $settings->return_policy}}</textarea>
                                </div>
                            </div>
                            <div class="form-group mt-5">
                                <input type="submit" name="addShipping" value="Update" class="btn btn-teal btn-block mg-b-10">
                             </div>


                                @endforeach

                            </div>







                        </form>
                        </div>
                    </div>

                </div>
            </div>
          </div>

@endsection
