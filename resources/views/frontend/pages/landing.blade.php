<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landing->product->name }} - {{ App\Models\Settings::first()->insta_link}}         </title>

  <link rel="shortcut icon" href="">

    <link href="https://fonts.googleapis.com/css?family=Raleway:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">

    <link href="frontEnd/plugins/font-awesome/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">



    <link rel="icon" type="image/x-icon" href="https://bikroyhat.com/frontend/favicon.png" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/all.min.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/flaticon/flaticon.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/nice-select.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/flags.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/slick.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/vendor/css/meanmenu.css" />
    <link rel="stylesheet" href="https://bikroyhat.com/frontend/css/style.css" />
    
    <style>
        iframe{
            width: 100%;
        }
    </style>
</head>

<body class="rev-7-body">
   
    <!-- preloader end -->



    
    <section class="rev-8-banner">
        <div class="container">
            
        
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-12 text-center">
                    <div class="rev-8-banner__txt">
                        <!--<h6>Exclusive Offer -20% Off This Week</h6>-->
                        
                        <div class="top-box-weight"> 
                            <h1 class="top-heading-title">  {{ $landing->heading }} </h1> 
                        </div>
                        
                        <p class="top-heading-subtitle"> {{ $landing->subheading }} </p>

                    </div>
                </div>
            </div>
          
            
            
            
        </div>
    </section>



    <section class="rev-8-banner-2">
        <div class="container">
            
             <div class="panel rev-6-panel">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-12 text-center">
                    <div class="rev-8-banner__txt">
                        <!--<h6>Exclusive Offer -20% Off This Week</h6>-->
                     
                        
                        
<video width="1240" height="360" controls>
                            <source src="{{ asset('backend/img/landing/' . $landing->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>                     
                        
                        <!--{!! $landing->video !!}                        -->
                    </div>
                </div>
            </div>
              </div>
            
        </div>
    </section>





   <section class="rev-8-banner-2" style="background:#eaf1f5">
        <div class="container">
            
             <div class="panel rev-6-panel" style="background: #187d25;">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-12 text-center">
                    <div class="rev-8-banner__txt">

                        <p class="top-heading-subtitle"> {{ $landing->heading_middle }}</p>

                        
                    </div>
                </div>
            </div>
              </div>
            
        </div>
    </section>




<div class="testimonial rev-8-banner-2 py-25">
        <div class="container">
            <div class="panel py-4 rounded">
                <div class="heading text-center">
                    <h2>{{ $landing->slider_title }}      </h2>
                </div>
                
                <div class="review-slider">
                    
                    @if($landing->slider)
                    
                        @foreach(json_decode($landing->slider)  as $slide)
                        
                        <div class="review-card ">
                            <div class="user">
                                <div class="part-img">
                                    <div class="avatar">
                                        <img src="{{ asset('backend/img/landing/'.$slide)  }} " alt="Avatar">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                   
                        @endforeach
                        
                    @endif
                    
                
                </div>
                
                
                
                
                
                <div class="heading text-center pt-3">
                      <a class="button-77"  href="#checkout_section"> অর্ডার করতে চাই </a>
                </div>
                
                
              
                
                
                
            </div>
        </div>
    </div>
    
    
    



<div class="testimonial rev-8-banner-2 py-25">
        <div class="container">
            <div class="row d-flex panel py-4 rounded">
                
                
               <div class="col-xl-9 col-lg-9 col-12 col-xs-12">
                    <div class="single-category rev-8-single-category">
                        
                        
                        <ul class="category-items" style="color: black !important">
                            
                        
                              {!! $landing->bullet !!}
                            
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>প্রতি কেজিতে ধরে ৩-৪  টি আম      </span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>বড় আকৃতির আম এর ওজন ৩০০ থেকে ৩৫০ গ্রাম</span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>আঁশ বিহীন আম, স্বাদে ও গন্ধে অতুলনীয়       </span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>প্রতি কেজিতে ধরে ৩-৪  টি আম      </span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>প্রতি কেজিতে ধরে ৩-৪  টি আম      </span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>বড় আকৃতির আম এর ওজন ৩০০ থেকে ৩৫০ গ্রাম</span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>আঁশ বিহীন আম, স্বাদে ও গন্ধে অতুলনীয়       </span> </li>-->
                            <!--<li><i class="fa-light fa-circle-check"></i> <span>প্রতি কেজিতে ধরে ৩-৪  টি আম      </span> </li>-->
                         
                 
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="best-selling-img-container" style="height: 100%;">
                        <img src="https://i.ibb.co/gSjbWrw/vecteezy-guaranteed-premium-quality-product-gold-label-golden-blue-13193429-removebg-preview.png" alt="product image" style="height: 100%;-o-object-fit: cover;object-fit: contain;border-radius: 8px;">
                    </div>
                </div>
                
              
                
                
                
            </div>
        </div>
    </div>


<div class="subscribe py-25">
        <div class="container">
            <div class="bg">
                
                <div class="row">
                    <div class="wrapper">
                        <div class="cta" href="#">
                            <del> {{ $landing->product->regular_price }}               TK          </del>
                            <span><svg width="66px" height="43px" viewBox="0 0 66 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="arrow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                  <path class="one" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                  <path class="two" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
                                  <path class="three" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
                                </g>
                              </svg></span> 
                            <span> {{ $landing->product->offer_price }}  TK</span>
                        </div>
                    </div>
                    
                 
                    
                </div>
                
                </br>
                <!--</br>-->
                
                <!--<div class="row d-flex justify-content-center">-->
                    
                <!--    <div class="col-xl-2 col-lg-4 col-4">-->
                <!--        <a href="#">-->
                <!--        <svg style="width:50px" enable-background="new 0 0 512 512" id="Layer_1" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient gradientUnits="userSpaceOnUse" id="SVGID_1_" x1="256" x2="256" y1="512" y2="-9.094947e-013"><stop offset="0" style="stop-color:#4CA2CD"></stop><stop offset="1" style="stop-color:#67B26F"></stop></linearGradient><circle cx="256" cy="256" fill="url(#SVGID_1_)" r="256"></circle><path d="M377.9,318.2l-40.5-40.5c-4.1-4.1-9.5-6.3-15.3-6.3c-5.8,0-11.2,2.2-15.3,6.3l-26.1,26.1L208.9,232  l26.1-26.1c8.4-8.4,8.4-22.1,0-30.5l-41.2-41.2c-4.1-4.1-9.5-6.3-15.3-6.3c-5.8,0-11.2,2.2-15.3,6.3L145,152.4  c-13.1,13.1-18.9,29.1-16.8,46.3c1.9,15.7,10.1,31.3,23.8,45L268.3,360c15.6,15.6,34,24.2,51.7,24.2h0c14.6,0,28.3-6,39.6-17.2  l18.3-18.3C386.4,340.3,386.4,326.6,377.9,318.2z M370,340.8l-18.3,18.3c-9.2,9.2-19.9,13.9-31.7,13.9c-14.7,0-30.2-7.4-43.7-20.9  L159.9,235.7c-19-19-32.3-50.2-7-75.4l18.3-18.3c2-2,4.6-3,7.3-3c2.8,0,5.4,1.1,7.3,3l41.2,41.2c4,4,4,10.6,0,14.6L197,228  c-1.1,1.1-1.6,2.5-1.6,4c0,1.5,0.6,2.9,1.6,4l79.8,79.8c1.1,1.1,2.5,1.6,4,1.6s2.9-0.6,4-1.6l30.1-30.1c2-2,4.6-3,7.3-3  s5.4,1.1,7.3,3l40.5,40.5C374,330.2,374,336.7,370,340.8z" fill="#FFFFFF"></path></svg>                    -->
                <!--        </a>-->
                <!--    </div>-->
                      
                    
               
                <!--    <div class="col-xl-2 col-lg-4 col-4">-->
                <!--        <a href="#">-->
                <!--        <svg style="width:50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 257"><defs><linearGradient id="b" x1="49.998%" x2="49.998%" y1="99.993%" y2="-.006%"><stop offset="0%" stop-color="#20B038"></stop><stop offset="100%" stop-color="#60D66A"></stop></linearGradient><filter filterUnits="objectBoundingBox" height="200%" id="a" width="200%" x="-50%" y="-50%"><feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="3"></feGaussianBlur></filter></defs><path d="M.297 244l17.155-62.594C6.904 163.09 1.282 142.284 1.34 121.014 1.34 54.362 55.588.172 122.24.172c32.34 0 62.71 12.577 85.488 35.412 22.835 22.835 35.412 53.205 35.354 85.488 0 66.651-54.249 120.842-120.9 120.842h-.058c-20.227 0-40.107-5.1-57.784-14.722L.297 244zm67.057-38.716l3.651 2.203c15.417 9.157 33.094 13.967 51.119 14.025h.058c55.35 0 100.44-45.033 100.44-100.44 0-26.835-10.432-52.046-29.384-71.057-18.952-19.01-44.222-29.442-71.056-29.442-55.408 0-100.499 45.033-100.499 100.44 0 18.953 5.274 37.441 15.359 53.438l2.376 3.825-10.142 37.035 38.078-10.027z" fill-opacity=".24" filter="url(#a)" transform="translate(6 7)"></path><path d="M5.781 237.539l16.37-59.7a115.214 115.214 0 01-15.397-57.581c0-63.535 51.744-115.221 115.22-115.221 30.852 0 59.758 12.02 81.508 33.77 21.75 21.751 33.714 50.714 33.714 81.508 0 63.534-51.744 115.22-115.221 115.22h-.057c-19.29 0-38.236-4.865-55.064-14.023L5.781 237.54z" fill="url(#b)" transform="translate(6 7)"></path><path d="M7.603 248.717L24.545 186.9c-10.417-18.087-15.97-38.636-15.912-59.642 0-65.824 53.575-119.342 119.4-119.342 31.938 0 61.93 12.42 84.426 34.972 22.551 22.552 34.972 52.545 34.915 84.427 0 65.824-53.575 119.342-119.4 119.342h-.056a119.45 119.45 0 01-57.067-14.539l-63.248 16.6zm66.224-38.235l3.606 2.175c15.226 9.044 32.683 13.795 50.485 13.852h.057c54.662 0 99.194-44.474 99.194-99.194 0-26.501-10.303-51.4-29.02-70.174-18.717-18.774-43.673-29.077-70.174-29.077-54.72 0-99.251 44.474-99.251 99.194 0 18.717 5.208 36.976 15.168 52.773l2.347 3.778-10.017 36.575 37.605-9.902z" fill="#FFF"></path><path d="M98.154 77.289c-2.233-4.98-4.58-5.095-6.697-5.152-1.717-.057-3.72-.057-5.724-.057-2.003 0-5.209.744-7.956 3.72-2.748 2.977-10.418 10.189-10.418 24.9 0 14.652 10.704 28.847 12.192 30.85 1.488 2.004 20.663 33.084 50.942 45.047 25.185 9.96 30.337 7.956 35.774 7.441 5.495-.515 17.63-7.212 20.148-14.195 2.461-6.983 2.461-12.936 1.717-14.195-.744-1.26-2.747-2.003-5.724-3.492-2.976-1.488-17.629-8.7-20.376-9.73-2.748-1.03-4.751-1.488-6.697 1.488-2.004 2.976-7.727 9.673-9.445 11.677-1.717 2.003-3.491 2.232-6.468.744-2.976-1.488-12.592-4.637-23.982-14.825-8.872-7.899-14.882-17.687-16.6-20.663-1.717-2.976-.171-4.58 1.317-6.067 1.316-1.317 2.976-3.492 4.465-5.209 1.488-1.717 2.003-2.976 2.976-4.98.973-2.003.515-3.72-.229-5.208-.744-1.489-6.582-16.199-9.215-22.094z" fill="#FFF"></path></svg>-->
                <!--        </a>-->
                <!--    </div>-->
             
                
                <!--    <div class="col-xl-2 col-lg-4 col-4">-->
                <!--        <a href="#">-->
                <!--        <svg style="width:50px" enable-background="new 0 0 128 128" id="Social_Icons" version="1.1" viewBox="0 0 128 128" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="_x34__stroke"><g id="Facebook_Messenger_1_"><rect clip-rule="evenodd" fill="none" fill-rule="evenodd" height="128" width="128"></rect><path clip-rule="evenodd" d="M70.36,79.802L54.062,62.42    L22.261,79.802l34.981-37.136l16.696,17.383l31.404-17.383L70.36,79.802z M64,0C28.654,0,0,26.531,0,59.259    c0,18.649,9.307,35.283,23.851,46.146V128l21.791-11.96c5.816,1.609,11.977,2.478,18.358,2.478c35.346,0,64-26.531,64-59.259    S99.346,0,64,0z" fill="#007FFF" fill-rule="evenodd" id="Facebook_Messenger"></path></g></g></svg>-->
                <!--        </a>-->
                <!--    </div>-->
              
                <!--</div>-->
                
                
                
                
                
               
                
            </div>
        </div>
    </div>




 <div class="tab-section py-4" style="background: url(https://s7template.com/tf/proone/preview/img/funfacts-bg.svg) center center no-repeat;" id="checkout_section">
     <div class="container">
         <div class="row panel p-5" style="box-shadow: 0 0 0 2px rgba(218,102,123,1), 8px 8px 0 0 rgba(218,102,123,1);">
             <div class="col-12">
                 <div class="tab-nav justify-content-center">
                     <button class="single-nav active" data-tab="checkOutTab" disabled>
                         <span class="txt">অর্ডারটি কনফার্ম করতে আপনার নাম, ঠিকানা, মোবাইল নাম্বার, লিখে অর্ডার কনফার্ম করুন বাটনে ক্লিক করুন</span>
                         <span class="sl-no">00</span>
                     </button>
                 </div>
                 <div class="tab-contents">
                    
                    
                    <form action="{{route('landing.order')}}" method="POST" class="form-row " >
                    @csrf
                        <div class="single-tab active" id="checkOutTab">
                         <div class="row gap-1">
                             <div class="col-xl-5 col-lg-5 col-md-5" style="border: 1px dashed #bdbdbd52;padding: 20px;">
                                 <div class="billing-details">
                                     
                                         <div class="col-md-12">
                                             <label for="customer_name">আপনার নাম </label>
                                             <input type="text"  class="form-control" id="name" name="name" placeholder="" required>
                                         </div>
                                         <div class="col-md-12">
                                             <label for="customer_address">আপনার ঠিকানা</label>
                                             <input type="text"  class="form-control" id="address" name="address" placeholder=""
                                                    required>
                                         </div>
                                         <div class="col-md-12">
                                             <label for="customer_phone">আপনার মোবাইল</label>
                                             <input type="text"  class="form-control" id="phone" name="phone" pattern="^(?:\+?88)?01[13-9]\d{8}$" onkeyup="checkScore()" placeholder="" required>
                                         </div>
                                       
                                         <div class="col-md-12">
                                             <label for="shipping_method">আপনার এরিয়া সিলেক্ট করুন</label>
                                             <select name="shipping_method"  id="shipping_method" class="form-control select wide" required>
                                                    <option value="" Selected diabled> Select Your Area</option>
                                                     @foreach($shippings as $shipping)

                                                     <option value="{{$shipping->id}}">{{$shipping->type}} </option>
                                                 @endforeach
                                                      
                                             </select>
                                         </div>
                                         
                                         <input type="hidden"   name="product_id" value="{{$landing->product->id}}">
                                                     <input type="hidden" name="price" value="{{$landing->product->offer_price}}">
                                                     <input type="hidden" name="sub_total" value="{{$landing->product->offer_price}}">
                                                     <input type="hidden" name="quantity" value="1">

                                        
  <br>  <br>
                                         <button type="submit" class="def-btn palce-order tab-next-btn btn-success w-100" id="conf_order_btn">অর্ডার কনফার্ম করুন <i class="fa-light fa-truck-arrow-right"></i></button>

                                     
                                 </div>
                             </div>
                             <div class="col-xl-6 col-lg-6 col-md-6" style="border: 1px dashed #bdbdbd52;">
                                 <div class="table-wrap revel-table">
                                     <div class="table-responsive">
                                         <table class="cart_table table text-center table-borderless" >
                                             <thead>
                                             <tr>
                                                 <th></th>
                                                 <th>Product</th>
                                                
                                                 <th>Quantity</th>
                                                  <th>Price</th>
                                                 <th></th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                            
                                             <tr>
                                                 <td>
                                                     <div class="product-img">
                                                         <img src="{{ asset('backend/img/products/'.$landing->product->image) ?? ''  }}" alt="Image">
                                                     </div>
                                                 </td>
                                                 <td>
                                                     <a href="#" class="product-name">
                                                         {{$landing->product->name}}
                                                         
                                                     </a>
                                                     
                                                 </td>
                                                 <td class="cart_qty">
                                                     <div class="product-count cart-product-count">
                                                         <div class="quantity rapper-quantity">
                                                             <input type="number" name="quantity" id="qty" min="1" class="qty" data-id="" value="1" readonly>
                                                             <div class="quantity-nav">
                                                                 <div class="quantity-button ">
                                                                     <i class="fa-solid fa-minus qty_minus" id="qty_minus" data-id=""></i>
                                                                 </div>
                                                                 <div class="quantity-button ">
                                                                     <i class="fa-solid fa-plus qty_plus" id="qty_plus" data-id=""></i>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </td>
                                                <td>
                                                    <span class="price-txt" id="prd_price"> {{$landing->product->offer_price}}</span>
                                                </td>


                                             </tr>
                                                
                                             </tbody>


                                         </table>
                                     </div>
                                 </div>






                                 <div class="payment-method">
                                     <div class="total-clone">
                                         <ul>
                                             <li>Sub Total <span class="price-txt" id="sub_total_l"> {{$landing->product->offer_price}}</span></li>
                                             
                                             <li>Shipping <span class="price-txt" id="cart_shipping_cost">0</span></li>
                                             <li class="total-price-wrap">Total <span class="price-txt" id="net_total">৳<span id="totalPrice2"> {{$landing->product->offer_price}} </span></span></li>
                                         </ul>
                                     </div>
                                     <div class="payment-option">

                                         <div class="single-payment-card">
                                             <div class="panel-header">
                                                 <div class="left-wrap">
                                                     <div class="form-check">
                                                         <input class="form-check-input" name="cash" type="checkbox" disabled checked>
                                                         <span class="sub-input"><i class="fa-regular fa-check"></i></span>
                                                     </div>
                                                     <span class="type">
                                                            Cash on delivery
                                                        </span>
                                                 </div>
                                                 <span class="icon">
                                                        <img src="{{asset('frontend/images/dollar.png')}} " alt="cash">
                                                    </span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     
                     </form>
                  
                 </div>
             </div>
         </div>
     </div>
 </div>





<div class="footer" >
    
    <div class="copyright">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>Developed By <a href="https://esonsoftit.com/" target="_blank">ESON SOFT IT</a> </p>
                </div>
                <div class="col-md-6">
                    <div class="part-img d-flex justify-content-md-end justify-content-center">
                        <img src="https://bikroyhat.com/frontend/images/payment-gateway.png" alt="Image" style="width: 350px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







   


<script src="https://bikroyhat.com/frontend/vendor/js/jquery-3.6.0.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/jquery.nice-select.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/jquery.flagstrap.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/slick.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/owl.carousel.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/jquery.meanmenu.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/jquery.syotimer.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/jquery-modal-video.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/bootstrap.bundle.min.js"></script>
<script src="https://bikroyhat.com/frontend/vendor/js/sweetalert2.min.js"></script>
<script src="https://bikroyhat.com/frontend/js/cart.js"></script>
<script src="https://bikroyhat.com/frontend/js/main.js"></script>
<script>
    
        
        
        
        $(".qty_plus").on("click",function(){

        var qtyy = parseInt($("#qty").val());
        qtyy = qtyy +1;
        $("#qty").val(qtyy);
         var prd_price = parseInt($("#prd_price").text()) ;
         var result =(prd_price * qtyy);
         $("#sub_total_l").text(result);
         
        var sub_total = parseInt($("#sub_total_l").text()) ;
        var shipping = parseInt($("#cart_shipping_cost").text());
        var result =(sub_total + shipping);
        $("#net_total").text(result);
                 
         
        });



        $(".qty_minus").on("click",function(){

        var qtyy = parseInt($("#qty").val());
        qtyy = qtyy - 1;
        $("#qty").val(qtyy);
         var prd_price = parseInt($("#prd_price").text()) ;
         var result =(prd_price * qtyy);
         $("#sub_total_l").text(result);
         
         var sub_total = parseInt($("#sub_total_l").text()) ;
        var shipping = parseInt($("#cart_shipping_cost").text());
        var result =(sub_total + shipping);
        $("#net_total").text(result);
         
        });


        $("#shipping_method").on("change",function(e){
            e.preventDefault();
            var shipping = $("#shipping_method").val();
            if(shipping){
                  $.ajax({
            type: 'GET',
            url: "/ajax_find_shipping/"+shipping,
            dataType:"json",
            success:function(data){

                var amount =parseInt(data.shipping.amount) ;
                $("#cart_shipping_cost").text(amount);
                var sub_total = parseInt($("#sub_total_l").text()) ;
                 var result =(sub_total + amount);
                 console.log(sub_total,amount);
                 $("#net_total").text(result);

            }

        });

            }else{
                $("#cart_shipping_cost").text(0);

                $("#net_total").text({{ App\Models\Cart::totalPrice() }});
            }
        });
</script>

</body>

</html>