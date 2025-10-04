<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use File;
use Image;
use Auth;
use Config;

class pagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $total_orders = Order::all();
        $settings = Settings::first();
        $users =User::all();
        $total_revenue = 0;
        foreach ($total_orders as $order) {
            $total_revenue += $order->total;
        }

        $recent_orders = Order::orderBy('id','desc')->take(10)->get();

        $today_orders = count(Order::whereRaw('Date(created_at) = CURDATE()')->get());
        $today_processing = count(Order::where('status',1)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_pending_pay = count(Order::where('status',6)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_hold = count(Order::where('status',3)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_canceled = count(Order::where('status',4)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_completed = count(Order::where('status',5)->whereRaw('Date(created_at) = CURDATE()')->get());
        
        $today_pendingdelivery = count(Order::where('status',2)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_ondelivery = count(Order::where('status',7)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_noresponse1 = count(Order::where('status',8)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_noresponse2 = count(Order::where('status',9)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_courierhold = count(Order::where('status',11)->whereRaw('Date(created_at) = CURDATE()')->get());
        $today_return= count(Order::where('status',12)->whereRaw('Date(created_at) = CURDATE()')->get());


        

        $last = Order::orderBy('id', 'desc')->first();

        return view('backend.pages.dashboard', compact('total_orders','users','settings','total_revenue','today_orders','today_processing','today_pending_pay','today_hold','today_canceled','today_completed','recent_orders','last','today_pendingdelivery','today_ondelivery','today_noresponse1','today_noresponse2','today_courierhold','today_return'));
    }
    public function r_store(Request $request)
    {
       $id =Auth::user()->id;
       $user =User::find($id);
       $user->password = Hash::make($request->new_pass);
       $user->save();
       return redirect()->route('admin.dashboard');

    }

    public function cart_atr_edit(Request $request,$id){

        $cart= Cart::find($id);
        $cart->attribute= json_encode($request->attribute);
        $cart->save();
        return redirect()->back();

    }

    public function p_i_e(Request $request,$id)
    {
        $product = Product::find($id);
        if( $request->image){
             if (File::exists('backend/img/products/' . $product->image)) {
                File::delete('backend/img/products/' . $product->image);

            }
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/products/' .$img;
            Image::make($image)->save($location);
            $product->image = $img;

        }
        $product->save();
        return redirect()->back();
    }
    public function p_i_d($id)
    {
       $product = Product::find($id);
       $product->image =NULL;
       $product->save();
       return redirect()->back();
    }


     public function p_g_e(Request $request,$id)
    {
        $product = Product::find($id);
        if( $request->image){
             if (File::exists('backend/img/products/' . $product->gallery_images)) {
                File::delete('backend/img/products/' . $product->gallery_images);

            }
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/products/' .$img;
            Image::make($image)->save($location);
            $product->gallery_images = $img;

        }
        $product->save();
        return redirect()->back();
    }
    public function p_g_d($id)
    {
       $product = Product::find($id);
       $product->gallery_images =NULL;
       $product->save();
       return redirect()->back();
    }


     public function p_s_e(Request $request,$id)
    {
        $product = Slider::find($id);
        if( $request->image){
             if (File::exists('backend/img/sliders/' . $product->name)) {
                File::delete('backend/img/sliders/' . $product->name);

            }
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/sliders/' .$img;
            Image::make($image)->save($location);
            $product->name = $img;

        }
        $product->save();
        return redirect()->back();
    }
    public function p_s_d($id)
    {
       $product = Slider::find($id);
       $product->name =NULL;
       $product->save();
       return redirect()->back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $settings = Settings::all();
         return view('backend.pages.settings', compact('settings'));
    }
    
    public function pathaoIndex()
    {
        $settings = Settings::all();
       
         return view('backend.pages.settings_pathao', compact('settings'));
    }
       public function pathaoUpdate(Request $request, $id)
    {
        $settings = Settings::find($id);
        
        
        
        $settings->pathao_store_id          = $request->pathao_store_id;
        $settings->pathao_client_id      = $request->pathao_client_id;

        $settings->pathao_client_secret      = $request->pathao_client_secret;
        $settings->pathao_email      = $request->pathao_email;
        $settings->pathao_password      = $request->pathao_password;
        $settings->pathao_status      = $request->pathao_status;
       
       
       
   
       
       
       
        $settings->save();
         $notification = array(
            'message'    => 'settings updated!',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);

    }
    
    public function steadfastUpdate(Request $request, $id)
    {
        $settings = Settings::find($id);
        
        $settings->steadfast_apikey          = $request->steadfast_apikey;
        $settings->steadfast_secretkey      = $request->steadfast_secretkey;
        $settings->steadfast_status      = $request->steadfast_status;
       
       
        $settings->save();
         $notification = array(
            'message'    => 'settings updated!',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);

    }
    
        public function redxUpdate(Request $request, $id)
    {
        $settings = Settings::find($id);
        
        $settings->redx_token          = $request->redx_token;
        $settings->redx_status      = $request->redx_status;
       
       
        $settings->save();
         $notification = array(
            'message'    => 'settings updated!',
            'alert-type' => 'info'
        );
        return redirect()->back()->with($notification);

    }
    
    

    public function page_index()
    {
        $settings = Settings::all();
         return view('backend.pages.settings_web', compact('settings'));
    }
    public function update_page(Request $request, $id)
    {

        $settings = Settings::find($id);
        $settings->about_us = $request->about_us;
        $settings->delivery_policy = $request->delivery_policy;
        $settings->return_policy = $request->return_policy;
        $settings->save();

        return redirect()->route('settings.web');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
      
    //     $settings = Settings::find($id);
    //     $settings->address =  $request->address;
    //     $settings->phone          = $request->phone;
    //     $settings->phone_two      = $request->phone_two;
    //     $settings->phone_three    = $request->phone_three;
    //     $settings->email          = $request->email;
    //     $settings->email_two      = $request->email_two;

    //     $settings->marque_text      = $request->marque_text;
    //     $settings->marque_status      = $request->marque_status;
    //     $settings->sms_status      = $request->sms_status;
    //     $settings->website_color      = $request->website_color;

    //     $settings->fb_link        = $request->fb_link;
    //     $settings->twitter_link   = $request->twitter_link;
    //     $settings->yt_link    = $request->yt_link;
    //     $settings->insta_link = $request->insta_link;
    //     $settings->copyright  = $request->copyright;
    //     if( $request->logo){
    //          if (File::exists('Backend/img/' . $settings->logo)) {
    //             File::delete('Backend/img/' . $settings->logo);

    //         }
    //         $image = $request->file('logo');
    //         $img = rand() . '.' . $image->getClientOriginalExtension();
    //         $location ='backend/img/' .$img;
    //         Image::make($image)->save($location);
    //         $settings->logo = $img;

    //     }
    //     if( $request->favicon){
    //          if (File::exists('Backend/img/' . $settings->favicon)) {
    //             File::delete('Backend/img/' . $settings->favicon);

    //         }
    //         $image = $request->file('favicon');
    //         $img = rand() . '.' . $image->getClientOriginalExtension();
    //         $location ='backend/img/' .$img;
    //         Image::make($image)->save($location);
    //         $settings->favicon = $img;

    //     }
    //     $settings->currency = $request->currency;
    //     $settings->bkash = $request->bkash;
    //     $settings->fb_pixel = $request->fb_pixel;
    //     $settings->about_us = $request->about_us;
    //     $settings->delivery_policy = $request->delivery_policy;
    //     $settings->return_policy = $request->return_policy;
    //     $settings->google_sheet = $request->google_sheet;
        
    //     $settings->number_block = $request->number_block;
    //     $settings->ip_block = $request->ip_block;
        
    //     $settings->qc_token = $request->qc_token;
        
    //     $settings->save();
    //      $notification = array(
    //         'message'    => 'settings updated!',
    //         'alert-type' => 'info'
    //     );
    //     return redirect()->back()->with($notification);

    // }
    public function update(Request $request, $id)
    {
        $settings = Settings::find($id);

        // Existing fields
        $settings->address        = $request->address;
        $settings->phone          = $request->phone;
        $settings->phone_two      = $request->phone_two;
        $settings->phone_three    = $request->phone_three;
        $settings->email          = $request->email;
        $settings->email_two      = $request->email_two;

        $settings->marque_text    = $request->marque_text;
        $settings->marque_status  = $request->marque_status;
        $settings->sms_status     = $request->sms_status;
        $settings->website_color  = $request->website_color;

        $settings->fb_link        = $request->fb_link;
        $settings->twitter_link   = $request->twitter_link;
        $settings->yt_link        = $request->yt_link;
        $settings->insta_link     = $request->insta_link;
        $settings->copyright      = $request->copyright;

        // New contact fields
        $settings->dial_up            = $request->dial_up;
        $settings->whatsapp_number    = $request->whatsapp_number;
        $settings->messenger_username = $request->messenger_username;
        $settings->imo_number         = $request->imo_number;

        // Logo upload
        if ($request->logo) {
            if (File::exists('backend/img/' . $settings->logo)) {
                File::delete('backend/img/' . $settings->logo);
            }
            $image = $request->file('logo');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/' . $img;
            Image::make($image)->save($location);
            $settings->logo = $img;
        }

        // Favicon upload
        if ($request->favicon) {
            if (File::exists('backend/img/' . $settings->favicon)) {
                File::delete('backend/img/' . $settings->favicon);
            }
            $image = $request->file('favicon');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/' . $img;
            Image::make($image)->save($location);
            $settings->favicon = $img;
        }

        $settings->currency        = $request->currency;
        $settings->bkash           = $request->bkash;
        $settings->fb_pixel        = $request->fb_pixel;
        $settings->about_us        = $request->about_us;
        $settings->delivery_policy = $request->delivery_policy;
        $settings->return_policy   = $request->return_policy;
        $settings->google_sheet    = $request->google_sheet;
        $settings->number_block    = $request->number_block;
        $settings->ip_block        = $request->ip_block;
        $settings->qc_token        = $request->qc_token;

        $settings->save();

        $notification = [
            'message'    => 'Settings updated!',
            'alert-type' => 'info'
        ];

        return redirect()->back()->with($notification);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
