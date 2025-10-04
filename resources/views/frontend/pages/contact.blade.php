@extends('frontend.layout.template')
@section('pageTitle')
    Contact
@endsection
@section('body-content')


    <div class="flash-deal py-4">
        <div class="container">
            <div class="panel">
                <div class="panel-header">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-2 col-6">
                            <h2 class="title">Contact info</h2>
                        </div>
                        <div class="col-lg-8 col-md-8 countdown-col">

                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="contact">
                        <div class="row justify-content-between">
                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <div class="contact-info">
                                    <h2 class="title"></h2>
                                    <ul>
                                        <li>
                                            <div class="part-icon">
                                                <span><i class="fa-solid fa-phone-flip"></i></span>
                                            </div>
                                            <div class="part-txt">
                                                <a href="mailto:Support@gmail.com">{{ $settings->email }}</a>
                                                <a href="tel:+1212-683-9756">{{ $settings->phone }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="part-icon">
                                                <span><i class="fa-solid fa-location-dot"></i></span>
                                            </div>
                                            <div class="part-txt">
                                                <span>{{ $settings->address }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="part-icon">
                                                <span><i class="fa-solid fa-clock"></i></span>
                                            </div>
                                            <div class="part-txt">
                                                <span>Mon - Sat : 8:00am - 5:00pm</span>
                                                <span>Sunday: Closed</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xxl-7 col-lg-7 col-md-6">
                                <div class="contact-form">
                                    <div class="map">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14594.426811590003!2d90.40170455!3d23.8680958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1661416116390!5m2!1sen!2sbd" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------- FEATURES SECTION START --------------------------------->
    <div class="features mb-5" id="feature">
        <div class="container">
            <div class="panel panel-shadow px-0">
                <div class="custom-row">
                    <div class="custom-col">
                        <div class="single-feature">
                            <div class="part-icon">
                                <span><i class="flaticon-money-saving"></i></span>
                            </div>
                            <div class="part-txt">
                                <h4>Free Delivery</h4>
                                <p>For all order over $99</p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-col">
                        <div class="single-feature">
                            <div class="part-icon">
                                <span><i class="flaticon-dollar"></i></span>
                            </div>
                            <div class="part-txt">
                                <h4>30 Days Return</h4>
                                <p>If goods have Problems</p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-col">
                        <div class="single-feature">
                            <div class="part-icon">
                                <span><i class="flaticon-credit-card"></i></span>
                            </div>
                            <div class="part-txt">
                                <h4>Secure Payment</h4>
                                <p>100% secure payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="custom-col">
                        <div class="single-feature">
                            <div class="part-icon">
                                <span><i class="flaticon-call-center"></i></span>
                            </div>
                            <div class="part-txt">
                                <h4>24/7 Support</h4>
                                <p>Dedicated support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------- FEATURES SECTION END --------------------------------->





@endsection
