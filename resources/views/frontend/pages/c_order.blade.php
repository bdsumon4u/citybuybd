@extends('frontend.layout.template')
@section('pageTitle', 'Confirm Order')
 @section('body-content')

     
 <div class="tab-section py-5">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <div class="tab-contents">
                     <div class="single-tab active" id="orderCompletedTab" style="padding: 15px;background: white;">
                         <div class="check-icon">
                             <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"> <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/> <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
                         </div>
                         <div class="order-complete-msg">
                             <h6 class="btn-bangla">প্রিয় {{ $order->name }}</h6>
                                 
                                     <h6 class="btn-bangla">  আপনার অর্ডার নাম্বার - {{ $order->id }}</h6>
                                         <h6 class="btn-bangla">   পণ্যের মূল্য - {{ $order->sub_total }} TK</h6>
                                            <h6 class="btn-bangla">   ডেলিভারি চার্জ - {{ $order->shipping_cost }} TK</h6>
                                            <h6 class="btn-bangla">  {{ $settings->yt_link }}  </h6>


                             <br/>
                                             <h6 class="btn-bangla">   ধন্যবাদন্তে</h6>
                                                <h5 class="btn-bangla"> {{ $settings->insta_link}}</h5>
                         </div>
                         <div class="order-complete-msg py-3">
                             <a href="{{url('/')}}" class="def-btn palce-order tab-next-btn btn-success px-5 btn-bangla" style="background-color: green">প্রোডাক্ট বাছাই করুন</a>
                         </div>

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

@endsection
