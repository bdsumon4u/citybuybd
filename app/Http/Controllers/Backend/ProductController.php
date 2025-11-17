<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Atr_item;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Landing;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\ProductAttribute;
use App\Models\Settings;
use App\Exports\ProductExport;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::first();
        $products = Product::orderBy('id','desc')->get();
        return view('backend.pages.product.manage', compact('products','settings'));
    }

    public function p_selected_status(Request $request)
    {

         $status= $request->p_status;
         $ids = $request->p_all_status;
         $orders =Product::whereIn('id',explode(",",$ids))->get();

         foreach($orders as $orders){
            $orders->status =$status;
            $orders->save();
         }

         return redirect()->back();


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       return view('backend.pages.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $product = new Product();
        $product->sku =$request->sku;

        if( $request->image){
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/products/' .$img;
//            Image::make($image)->encode('webp', 80)->save($location);
            Image::make($image)->resize(800, 800)->save($location);

            $product->image = $img;

        }

        if($request->gallery_images){


        if ($request->hasFile('gallery_images')) {
          $image = $request->file('gallery_images');
          foreach ($image as $files) {
              $file_name = rand() . "." . $files->getClientOriginalExtension();
              $destinationPath = 'backend/img/products/'.$file_name;
              Image::make($files)->resize(800, 800)->save($destinationPath);


              $data[] = $file_name;
          }
      }

        $product->gallery_images=json_encode($data);
    }

            if($request->video){
                 $video = $request->file('video');
                $extension = $video->getClientOriginalExtension();
                $name = $video->getClientOriginalName();
                $fileName = rand().".".$extension;
                $video->move('backend/img/products/video',$fileName);

                 $product->video = $fileName;
            }





        $product->name                     =$request->name;
        $product->slug=Str::slug($request->name);
        $product->model                    = $request->model;
        $product->stock                    =$request->stock;
        $product->serial                    =$request->serial;
        $product->description              =$request->description  ;
        $product->category_id              =$request->category_id;
        $product->subcategory_id              =$request->subcategory_id;
        $product->childcategory_id              =$request->childcategory_id;
        $product->brand_id              =$request->brand_id;


if ($request->has('atr') && is_array($request->atr)) {
            $product->atr = json_encode($request->atr);
        } else {
            $product->atr = null;
        }

        if ($request->has('att_item') && is_array($request->att_item)) {
            $product->atr_item = json_encode($request->att_item);
        } else {
            $product->atr_item = null;
        }

    //   if($request->atr){
    //         $product->atr                 =json_encode($request->atr);
    //     $product->atr_item                 =json_encode($request->att_item);
    //     }


        $product->regular_price            =$request->regular_price;
        $product->offer_price              =$request->offer_price;

        $product->shipping                   =$request->shipping;
        $product->inside                   =$request->inside;
        $product->outside                   =$request->outside;

        $product->assign                   =$request->assign;
        $product->status                   =$request->status;
        $product->save();



        $notification = array(
            'message'    => 'product created!',
            'alert-type' => 'info'
        );
        return redirect()->route('product.manage')->with($notification);

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
    public function edit($id)
    {

        $product= Product::find($id);

        $subcategory= Subcategory::find($product->subcategory_id);
        $childcategory= Childcategory::find($product->childcategory_id);

        $product_attributs = ProductAttribute::with('get_atr_item')->get();

        if (!is_null($product)) {
            return view('backend.pages.product.edit', compact('product','product_attributs','subcategory','childcategory'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $product =Product::find($id);
        $product->sku                      =$request->sku;
        if( $request->image){
            if (File::exists('backend/img/products/' . $product->image)) {
                File::delete('backend/img/products/' . $product->image);

            }
            $image = $request->file('image');
            $img = rand() . '.' . $image->getClientOriginalExtension();
            $location = 'backend/img/products/' .$img;
//            Image::make($image)->encode('webp', 80)->save($location);
//            Image::make($image)->encode('webp', 80)->resize(400, 400, function ($constraint) {
//                $constraint->aspectRatio();
//            })->save($location);
            Image::make($image)->resize(800, 800)->save($location);

            $product->image = $img;

        }
        if($request->gallery_images){

            if(!is_null($product->gallery_images)){
                foreach (json_decode($product->gallery_images) as $area)
                {
                  if (File::exists('backend/img/products/' . $area)) {
                File::delete('backend/img/products/' . $area);

            }
                }
            }
                            if ($request->hasFile('gallery_images')) {
          $image = $request->file('gallery_images');
          foreach ($image as $files) {

              $file_name = rand() . "." . $files->getClientOriginalExtension();
              $destinationPath = 'backend/img/products/'.$file_name;
//              $image = Image::make($request->file($files));


              Image::make($files)->resize(800, 800)->save($destinationPath);
//              Image::make($files)->save($destinationPath);
//              $image->save(public_path($file_name));




//              $files->move($destinationPath, $file_name);
//              Image::make($files)->encode('webp', 80)->resize(400, 400, function ($constraint) {
//                  $constraint->aspectRatio();
//              })->save($destinationPath);
              $data[] = $file_name;
          }
      }
        $product->gallery_images=json_encode($data);

        }

          if($request->video){
            if (File::exists('backend/img/products/video/' . $product->video)) {
            File::delete('backend/img/products/video/' . $product->video);
            }
            $video = $request->file('video');
            $extension = $video->getClientOriginalExtension();
            $name = $video->getClientOriginalName();
            $fileName = rand().".".$extension;
            $video->move('backend/img/products/video',$fileName);

            $product->video = $fileName;
            }




        $product->name                     =$request->name;
        $product->slug=Str::slug($request->name);
        $product->model                    = $request->model;
        $product->serial                   =$request->serial;
        $product->stock                    =$request->stock;
        $product->description              =$request->description  ;
        $product->category_id              =$request->category_id;
        $product->subcategory_id              =$request->subcategory_id;
        $product->childcategory_id              =$request->childcategory_id;
        $product->brand_id              =$request->brand_id;


        if ($request->has('atr') && is_array($request->atr)) {
            $product->atr = json_encode($request->atr);
        } else {
            $product->atr = null;
        }

        if ($request->has('att_item') && is_array($request->att_item)) {
            $product->atr_item = json_encode($request->att_item);
        } else {
            $product->atr_item = null;
        }

        // if($request->atr){
        //     $product->atr                 =json_encode($request->atr);
        // $product->atr_item                 =json_encode($request->att_item);
        // }


        $product->regular_price            =$request->regular_price;
        $product->offer_price              =$request->offer_price;

        $product->shipping                   =$request->shipping;
        $product->inside                   =$request->inside;
        $product->outside                   =$request->outside;

        $product->assign                    =$request->assign;
        $product->status                   =$request->status;
        $product->save();



        $notification = array(
            'message'    => 'product updated!',
            'alert-type' => 'info'
        );
        return redirect()->route('product.manage')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = product::find($id);
        if (!is_null($product)) {
            $product->delete();
            $notification = array(
            'message'    => 'product deleted!',
            'alert-type' => 'error'
        );
        return redirect()->route('product.manage')->with($notification);
        }
    }
     public function assign_dlt($id)
    {
        $product = Product::find($id);
        if (!is_null($product)) {
            $product->assign ="";
            $product->save();
            $notification = array(
            'message'    => 'Assign deleted!',
            'alert-type' => 'error'
        );
        return redirect()->route('product.manage')->with($notification);
        }
    }

    public function deleteChecketProducts(Request $request){
         $ids = $request->ids;
        if (!is_null($ids)) {
            Product::whereIn('id',$ids)->delete();
        $notification = array(
            'message'    => 'product deleted!',
            'alert-type' => 'error'
        );
        }else{
            $notification = array(
            'message'    => 'No Product Selected!',
            'alert-type' => 'error'
        );
        }



        return redirect()->route('product.manage')->with($notification);

    }
    public function exportIntoExcel(){

        return Excel::download(new ProductExport, 'productlist.xlsx');
    }


// Landing Pages

 public function landingindex()
    {
        $settings = Settings::first();
        // $products = Product::select('id','name')->get();
        $landings = Landing::with('product')->get();


        return view('backend.pages.landing.manage', compact('landings','settings'));
    }

    public function landingcreate()
    {

  $products = Product::select('id','name')->get();
       return view('backend.pages.landing.create', compact('products'));
    }


  public function landingstore(Request $request)
    {
        $product= Product::find($request->product_id);

        $landing = new Landing();



        if ($request->video) {
            $video = $request->file('video');
            $extension = $video->getClientOriginalExtension();
            $fileName = rand() . '.' . $extension;
            $video->move(public_path('backend/img/landing'), $fileName);
            $landing->video = $fileName;
        }


        if($request->gallery_images){

            if ($request->hasFile('gallery_images')) {
              $image = $request->file('gallery_images');
              foreach ($image as $files) {
                  $file_name = rand() . "." . $files->getClientOriginalExtension();
                  $destinationPath = 'backend/img/landing/'.$file_name;
                  Image::make($files)->resize(400, 400)->save($destinationPath);
                  $data[] = $file_name;
              }
      }

        $landing->slider=json_encode($data);
    }

        $landing->heading     =$request->heading;


        $landing->slug = $product->slug;
        $landing->subheading  =$request->subheading;
        $landing->heading_middle   =$request->heading_middle;
        $landing->slider_title    =$request->slider_title;
        $landing->bullet   =$request->bullet  ;
        $landing->product_id    =$request->product_id;
        $landing->video     =$request->video;

        $landing->old_price  =$request->old_price;
        $landing->new_price  =$request->new_price;
        $landing->phone  =$request->phone;
        $landing->home_delivery  =$request->home_delivery;

        $landing->save();

        $notification = array(
            'message'    => 'product created!',
            'alert-type' => 'info'
        );
        return redirect()->route('landing.manage')->with($notification);

    }



    public function landingedit($id)
    {

        $landing= Landing::find($id);
        $settings = Settings::first();
        // $products = Product::select('id','name')->get();
        $products = Product::select('id','name')->get();

        if (!is_null($landing)) {
            return view('backend.pages.landing.edit', compact('landing','products','settings'));
        }
    }


    public function landingupdate(Request $request, $id)
    {
        $landing =Landing::find($id);
        $product= Product::find($request->product_id);


   if ($request->video) {
            $video = $request->file('video');
            $extension = $video->getClientOriginalExtension();
            $fileName = rand() . '.' . $extension;
            $video->move(public_path('backend/img/landing'), $fileName);
            $landing->video = $fileName;
        }

        if($request->gallery_images){

            if(!is_null($landing->gallery_images)){
                foreach (json_decode($landing->gallery_images) as $area)
                {
                    if (File::exists('backend/img/landing/' . $area))
                    {
                        File::delete('backend/img/landing/' . $area);
                    }
                }
            }



            if ($request->hasFile('gallery_images')) {
            $image = $request->file('gallery_images');
            foreach ($image as $files) {

                  $file_name = rand() . "." . $files->getClientOriginalExtension();
                  $destinationPath = 'backend/img/landing/'.$file_name;
                  Image::make($files)->resize(400, 400)->save($destinationPath);
                  $data[] = $file_name;
            }
      }
        $landing->slider=json_encode($data);

        }

        $landing->heading     =$request->heading;
        $landing->slug = $product->slug;
        $landing->subheading  =$request->subheading;
        $landing->heading_middle   =$request->heading_middle;
        $landing->slider_title    =$request->slider_title;
        $landing->bullet   =$request->bullet  ;
        $landing->product_id    =$request->product_id;
        // $landing->video     =$request->video;
        $landing->status  =$request->status;

        $landing->old_price  =$request->old_price;
        $landing->new_price  =$request->new_price;
        $landing->phone  =$request->phone;
        $landing->home_delivery  =$request->home_delivery;

        $landing->save();

        $notification = array(
            'message'    => 'Landing updated!',
            'alert-type' => 'info'
        );
        return redirect()->route('landing.manage')->with($notification);

    }



  public function landingdestroy($id)
    {


        $landing = Landing::find($id);

        if (!is_null($landing)) {
            $landing->delete();
            $notification = array(
            'message'    => 'Landing deleted!',
            'alert-type' => 'error'
        );
        return redirect()->route('landing.manage')->with($notification);
        }
    }







}
