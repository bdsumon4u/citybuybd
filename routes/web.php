<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PathaoController;
use App\Http\Controllers\Backend\RedXController;
use App\Http\Controllers\PushSubscriptionController;


Route::middleware('auth')->group(function () {
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');
});

Route::get('total-order-list','App\Http\Controllers\Backend\OrderController@total_order_list')->name('total_order_list');
Route::get('qc_report/{number}','App\Http\Controllers\Backend\OrderController@qc_report')->name('qc_report');
Route::get('total-order-custom-date/{date_from}/{date_to}','App\Http\Controllers\Backend\OrderController@total_order_custom_date')->name('total_order_custom_date');
Route::get('total-order-fixed-date/{count}','App\Http\Controllers\Backend\OrderController@total_order_fixed_date')->name('total_order_fixed_date');


Route::get('total-order-product/{date_from}/{date_to}/{prd}','App\Http\Controllers\Backend\ReportController@total_order_product')->name('total_order_product');
Route::get('total-order-employee','App\Http\Controllers\Backend\ReportController@total_order_employee')->name('total_order_employee');


// **** FRONTEND ROUTE  START ********
Route::get('hot_deals', function(){
    $products = App\Models\Product::whereNotNull('offer_price')->where('status',1)->orderBy('id','desc')->paginate(18);
    $settings = App\Models\Settings::first();

    return view('frontend.pages.hot_deal',compact('products','settings'));

  });
Route::get('all-Products', function(){
    $products = App\Models\Product::where('status',1)->orderBy('id','desc')->get();
    $settings = App\Models\Settings::first();
    return view('frontend.pages.allProducts',compact('products','settings'));
  });
Route::get('confirm-order', function(){


    return view('frontend.pages.c_order');

  })->name('c_order');



 Route::get('/get-subcategory/{id}', function($id){
    return json_encode(App\Models\Subcategory::where('category_id', $id)->get());
});
 Route::get('/get-childcategory/{id}', function($id){
    return json_encode(App\Models\Childcategory::where('subcategory_id', $id)->get());
});




 // frontend cart start

    //  Route::get('/','App\Http\Controllers\Frontend\CartController@index')->name('cart.items');
    //  Route::post('/store','App\Http\Controllers\Frontend\CartController@store')->name('cart.store');
    //  Route::post('/o_store','App\Http\Controllers\Frontend\CartController@o_store')->name('o_cart.store');

      Route::post('/o_store','App\Http\Controllers\Frontend\CartController@o_store')->name('o_cart.store');
     Route::get('/cart_plus','App\Http\Controllers\Frontend\CartController@cart_plus');

     Route::post('update/{id}','App\Http\Controllers\Frontend\CartController@update')->name('cart.update');

      Route::get('destroy/{id}','App\Http\Controllers\Frontend\CartController@destroy')->name('cart.destroy');
      Route::get('admin_cart_dlt/{id}/{order}','App\Http\Controllers\Frontend\CartController@admin_cart_dlt')->name('admin_cart_dlt');


  // frontend cart end



  //pathao web hook
Route::post('pathao-status-update',         [PathaoController::class,     'pathaoStatusUpdate'])->name('pathao.status.update');
//redx webhook
Route::get('redx/areas',                    [RedXController::class,       'getAreas'])->name('redx.areas');
Route::post('redx-status-update',           [RedXController::class,       'redxStatusUpdate'])->name('redx.status.update');



//   Route::post('pathao-status-update',         [PathaoController::class,     'pathaoStatusUpdate'])->name('pathao.status.update')->middleware('webhookCheck');

  Route::prefix('pathao')->name('pathao.')->group(function(){
    Route::get('get-stores',               [PathaoController::class, 'GetStores'])->name('get.stores');
    Route::get('get-cities',               [PathaoController::class, 'GetCities'])->name('get.cities');
    Route::get('get-zones',                [PathaoController::class, 'GetZones'])->name('get.zones');
    Route::get('get-areas',                [PathaoController::class, 'GetAreas'])->name('get.areas');
});


Route::post('/get_city','App\Http\Controllers\Backend\OrderController@get_city');
Route::post('/get_zone','App\Http\Controllers\Backend\OrderController@get_zone');
 Route::get('/get-city/{id}', function($id){
    return json_encode(App\Models\City::where('courier_id', $id)->get());
});

 Route::get('/get-zone/{id}', function($id){
    return json_encode(App\Models\Zone::where('city', $id)->get());
});


 //
 Route::get('/', 'App\Http\Controllers\Frontend\PagesController@index')->name('homepage')->middleware('cache.response');
Route::get('/details/{id}', 'App\Http\Controllers\Frontend\PagesController@details')->name('details')->middleware('cache.response');
Route::get('/checkout', 'App\Http\Controllers\Frontend\PagesController@checkout')->name('checkout');



Route::post('/ajax_get_shipp_meth', 'App\Http\Controllers\Frontend\PagesController@ajax_get_shipp_meth')->name('ajax.get.shipp.meth');


Route::post('/order', 'App\Http\Controllers\Frontend\PagesController@order')->name('order');
Route::post('/landing-order', 'App\Http\Controllers\Frontend\PagesController@landingorder')->name('landing.order');
Route::get('/search','App\Http\Controllers\Frontend\PagesController@search')->name('search');
Route::get('/ajax_find_shipping/{id}','App\Http\Controllers\Frontend\PagesController@ajax_find_shipping');

// Route::get('/cart_plus_admin/{id}/{order}','App\Http\Controllers\Frontend\PagesController@cart_plus_admin')->middleware("auth","admin");

// Route::get('/cart_minus_admin/{id}/{order}','App\Http\Controllers\Frontend\PagesController@cart_minus_admin')->middleware("auth","admin");

// Route::get('/cart_plus','App\Http\Controllers\Frontend\PagesController@cart_plus');
// Route::get('/qty_minus_admin/{id}','App\Http\Controllers\Frontend\PagesController@qty_minus');
// Route::get('/qty_minus','App\Http\Controllers\Frontend\PagesController@qty_minus');
// Route::get('/cart_input/{id}/{input}','App\Http\Controllers\Frontend\PagesController@cart_input');


Route::get('category/{id}','App\Http\Controllers\Frontend\PagesController@category')->name('category')->middleware('cache.response');
Route::get('subcategory/{id}','App\Http\Controllers\Frontend\PagesController@subcategory')->name('subcategory')->middleware('cache.response');
Route::get('childcategory/{id}','App\Http\Controllers\Frontend\PagesController@childcategory')->name('childcategory')->middleware('cache.response');

 //

Route::get('contact','App\Http\Controllers\Frontend\PagesController@contact')->name('front.contact')->middleware('cache.response');
Route::get('about','App\Http\Controllers\Frontend\PagesController@about')->name('front.about')->middleware('cache.response');
Route::get('term-condition','App\Http\Controllers\Frontend\PagesController@termCondition')->name('front.termCondition')->middleware('cache.response');

Route::get('landing/{id}','App\Http\Controllers\Frontend\PagesController@landing')->name('front.landing')->middleware('cache.response');





// **** FRONTEND ROUTE  END   ********





 Route::get('/images', function(){
    return view('backend.pages.image.manage');

  })->middleware('auth','admin');


Route::get('/ajax_find_product/{id}','App\Http\Controllers\Backend\OrderController@ajax_find_product');

Route::get('/ajax_find_courier/{id}','App\Http\Controllers\Backend\OrderController@ajax_find_courier');

Route::get('/admin_cart/{id}','App\Http\Controllers\Backend\OrderController@admin_cart');
Route::get('/admin_cart_update/{id}/{order}','App\Http\Controllers\Backend\OrderController@admin_cart_update');


Route::post('/get_city','App\Http\Controllers\Backend\OrderController@get_city');
Route::post('/get_zone','App\Http\Controllers\Backend\OrderController@get_zone');
 Route::get('/get-city/{id}', function($id){
    return json_encode(App\Models\City::where('courier_id', $id)->get());
});

 Route::get('/get-zone/{id}', function($id){
    return json_encode(App\Models\Zone::where('city', $id)->get());
});







Route::group( ['prefix'=>'admin'], function(){

     Route::get('/fetch-order/{id}', 'App\Http\Controllers\Backend\OrderController@fetch_order')->name('fetch_order')->middleware('auth','admin');
 Route::get('/fetch-product/{id}', 'App\Http\Controllers\Backend\OrderController@fetch_product')->name('fetch_product')->middleware('auth','admin');

    // admin dashboard page page
    Route::get('/dashboard', 'App\Http\Controllers\Backend\PagesController@dashboard')->name('admin.dashboard')->middleware('auth','admin');


    Route::get('customers', 'App\Http\Controllers\Backend\UserController@customerIndex')->middleware('auth','admin')->name('customer.manage');

//  Route::get('ordered_product_c', function(){
//    return view('backend.pages.report.ordered_product_c');
//  })->middleware('auth','admin')->name('ordered_product_c');

  Route::get('/employee-orders', 'App\Http\Controllers\Backend\ReportController@employee_orders')->middleware('auth','admin')->name('employee_orders');

  Route::get('ordered_product_c', 'App\Http\Controllers\Backend\ReportController@products_orders')->middleware('auth','admin')->name('ordered_product_c');

  Route::get('/employee-orders-search', 'App\Http\Controllers\Backend\ReportController@employee_orders_search')->middleware('auth','admin')->name('employee_orders_search');

  Route::post('/cart_atr_edit/{id}', 'App\Http\Controllers\Backend\PagesController@cart_atr_edit')->middleware('auth')->name('cart_atr_edit');

//   Route::get('/employee_status/employee={employee?}/status={status?}', 'App\Http\Controllers\Backend\ReportController@employee_status')->middleware('auth','admin')->name('employee_status');

   Route::get('/employee_status/{employee?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', 'App\Http\Controllers\Backend\ReportController@employee_status')->middleware('auth','admin')->name('employee_status');


  Route::get('/product_orders', 'App\Http\Controllers\Backend\ReportController@product_orders')->middleware('auth','admin')->name('product_orders');

   Route::get('/product_orders_search/', 'App\Http\Controllers\Backend\ReportController@product_orders_search')->middleware('auth','admin')->name('product_orders_search');

    // Route::get('/product_status/product={product?}/status={status?}', 'App\Http\Controllers\Backend\ReportController@product_status')->middleware('auth','admin')->name('product_status');

    Route::get('/product_status/{product?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', 'App\Http\Controllers\Backend\ReportController@product_status')->middleware('auth','admin')->name('product_status');





// attribute start

    Route::group(['prefix'=>'attribute'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\AttributeController@index')->middleware('auth','admin')->name('attribute.manage');

        Route::post('/store', 'App\Http\Controllers\Backend\AttributeController@store')->middleware('auth','admin')->name('attribute.store');

        Route::post('/update/{id}', 'App\Http\Controllers\Backend\AttributeController@update')->middleware('auth','admin')->name('attribute.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\AttributeController@destroy')->middleware('auth','admin')->name('attribute.destroy');

 Route::post('/item_store', 'App\Http\Controllers\Backend\AttributeController@item_store')->middleware('auth','admin')->name('attribute.item_store');

        Route::post('/item_update/{id}', 'App\Http\Controllers\Backend\AttributeController@item_update')->middleware('auth','admin')->name('attribute.item_update');
        Route::post('/item_destroy/{id}', 'App\Http\Controllers\Backend\AttributeController@item_destroy')->middleware('auth','admin')->name('attribute.item_destroy');


    });
// attribute end

  Route::get('customer-export', 'App\Http\Controllers\Backend\OrderController@exportIntoExcel')->middleware('auth','admin')->name('customer.export');

 Route::post('p_i_e/{id}', 'App\Http\Controllers\Backend\PagesController@p_i_e')->middleware('auth','admin')->name('p_i_e');
 Route::get('p_i_d/{id}', 'App\Http\Controllers\Backend\PagesController@p_i_d')->middleware('auth','admin')->name('p_i_d');

 Route::post('p_g_e/{id}', 'App\Http\Controllers\Backend\PagesController@p_g_e')->middleware('auth','admin')->name('p_g_e');
 Route::get('p_g_d/{id}', 'App\Http\Controllers\Backend\PagesController@p_g_d')->middleware('auth','admin')->name('p_g_d');

  Route::post('p_s_e/{id}', 'App\Http\Controllers\Backend\PagesController@p_s_e')->middleware('auth','admin')->name('p_s_e');
 Route::get('p_s_d/{id}', 'App\Http\Controllers\Backend\PagesController@p_s_d')->middleware('auth','admin')->name('p_s_d');



// category group
    Route::group(['prefix'=>'settings'],function(){

        Route::get('/', 'App\Http\Controllers\Backend\PagesController@edit')->middleware('auth','admin')->name('settings.edit');
        Route::get('/page', 'App\Http\Controllers\Backend\PagesController@page_index')->middleware('auth','admin')->name('settings.web');
        Route::post('update/{id}', 'App\Http\Controllers\Backend\PagesController@update')->middleware('auth','admin')->name('settings.update');
        Route::post('update/page/{id}', 'App\Http\Controllers\Backend\PagesController@update_page')->middleware('auth','admin')->name('settings.update.page');

        Route::get('/pathao-api', 'App\Http\Controllers\Backend\PagesController@pathaoIndex')->middleware('auth','admin')->name('settings.pathaoIndex');
        Route::post('pathao-api/update/{id}', 'App\Http\Controllers\Backend\PagesController@pathaoUpdate')->middleware('auth','admin')->name('settings.pathaoUpdate');
        Route::post('steadfast-api/update/{id}', 'App\Http\Controllers\Backend\PagesController@steadfastUpdate')->middleware('auth','admin')->name('settings.steadfastUpdate');

        Route::post('redxUpdate-api/update/{id}', 'App\Http\Controllers\Backend\PagesController@redxUpdate')->middleware('auth','admin')->name('settings.redxUpdate');

        Route::get('/whatsapp', 'App\Http\Controllers\Backend\PagesController@whatsappIndex')->middleware('auth','admin')->name('settings.whatsappIndex');
        Route::post('whatsapp/update/{id}', 'App\Http\Controllers\Backend\PagesController@whatsappUpdate')->middleware('auth','admin')->name('settings.whatsappUpdate');

        Route::get('/sms', 'App\Http\Controllers\Backend\PagesController@smsIndex')->middleware('auth','admin')->name('settings.smsIndex');
        Route::post('sms/update/{id}', 'App\Http\Controllers\Backend\PagesController@smsUpdate')->middleware('auth','admin')->name('settings.smsUpdate');

        Route::get('/manual-order-types', 'App\Http\Controllers\Backend\PagesController@manualOrderTypesIndex')->middleware('auth','admin')->name('settings.manualOrderTypesIndex');
        Route::post('manual-order-types/store', 'App\Http\Controllers\Backend\PagesController@manualOrderTypeStore')->middleware('auth','admin')->name('settings.manualOrderTypeStore');
        Route::post('manual-order-types/update/{id}', 'App\Http\Controllers\Backend\PagesController@manualOrderTypeUpdate')->middleware('auth','admin')->name('settings.manualOrderTypeUpdate');
        Route::delete('manual-order-types/delete/{id}', 'App\Http\Controllers\Backend\PagesController@manualOrderTypeDestroy')->middleware('auth','admin')->name('settings.manualOrderTypeDestroy');

        Route::get('/order-notes', 'App\Http\Controllers\Backend\PagesController@orderNotesIndex')->middleware('auth','admin')->name('settings.orderNotesIndex');
        Route::post('order-notes/store', 'App\Http\Controllers\Backend\PagesController@orderNoteStore')->middleware('auth','admin')->name('settings.orderNoteStore');
        Route::post('order-notes/update/{id}', 'App\Http\Controllers\Backend\PagesController@orderNoteUpdate')->middleware('auth','admin')->name('settings.orderNoteUpdate');
        Route::delete('order-notes/delete/{id}', 'App\Http\Controllers\Backend\PagesController@orderNoteDestroy')->middleware('auth','admin')->name('settings.orderNoteDestroy');

    });

    Route::group(['prefix' => 'marketing'], function () {
        Route::get('/', 'App\Http\Controllers\Backend\MarketingController@index')->middleware('auth', 'admin')->name('marketing.index');
        Route::get('/filter', 'App\Http\Controllers\Backend\MarketingController@filter')->middleware('auth', 'admin')->name('marketing.filter');
        Route::post('/send', 'App\Http\Controllers\Backend\MarketingController@sendBulkSms')->middleware('auth', 'admin')->name('marketing.send');
    });

    Route::get('/user_products', function(){
    return view('backend.pages.user_products');

  })->middleware('auth','admin')->name('user_products');

    Route::get('reset', function(){
        return view('backend.pages.reset');
    })->name('admin.reset')->middleware('auth','admin');

    Route::post('r_store', 'App\Http\Controllers\Backend\PagesController@r_store')->name('admin.r_store')->middleware('auth','admin');




     // category group
    Route::group(['prefix'=>'/category'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\Categorycontroller@index')->middleware('auth','admin')->name('category.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\Categorycontroller@create')->middleware('auth','admin')->name('category.create');
        Route::post('/store', 'App\Http\Controllers\Backend\Categorycontroller@store')->middleware('auth','admin')->name('category.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\Categorycontroller@edit')->middleware('auth','admin')->name('category.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\Categorycontroller@update')->middleware('auth','admin')->name('category.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\Categorycontroller@destroy')->middleware('auth','admin')->name('category.destroy');

    });

    Route::group(['prefix'=>'/subcategory'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\Categorycontroller@sub_index')->middleware('auth','admin')->name('subcategory.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\Categorycontroller@sub_create')->middleware('auth','admin')->name('subcategory.create');
        Route::post('/store', 'App\Http\Controllers\Backend\Categorycontroller@sub_store')->middleware('auth','admin')->name('subcategory.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\Categorycontroller@sub_edit')->middleware('auth','admin')->name('subcategory.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\Categorycontroller@sub_update')->middleware('auth','admin')->name('subcategory.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\Categorycontroller@sub_destroy')->middleware('auth','admin')->name('subcategory.destroy');

    });

    Route::group(['prefix'=>'/childcategory'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\Categorycontroller@child_index')->middleware('auth','admin')->name('childcategory.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\Categorycontroller@child_create')->middleware('auth','admin')->name('childcategory.create');
        Route::post('/store', 'App\Http\Controllers\Backend\Categorycontroller@child_store')->middleware('auth','admin')->name('childcategory.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\Categorycontroller@child_edit')->middleware('auth','admin')->name('childcategory.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\Categorycontroller@child_update')->middleware('auth','admin')->name('childcategory.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\Categorycontroller@child_destroy')->middleware('auth','admin')->name('childcategory.destroy');

    });







    // brand group
    Route::group(['prefix'=>'/brand'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\Categorycontroller@indexBrand')->middleware('auth','admin')->name('brand.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\Categorycontroller@createBrand')->middleware('auth','admin')->name('brand.create');
        Route::post('/store', 'App\Http\Controllers\Backend\Categorycontroller@storeBrand')->middleware('auth','admin')->name('brand.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\Categorycontroller@editBrand')->middleware('auth','admin')->name('brand.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\Categorycontroller@updateBrand')->middleware('auth','admin')->name('brand.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\Categorycontroller@destroyBrand')->middleware('auth','admin')->name('brand.destroy');

    });


     // slider group
    Route::group(['prefix'=>'/slider'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\SliderController@index')->middleware('auth','admin')->name('slider.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\SliderController@create')->middleware('auth','admin')->name('slider.create');
        Route::post('/store', 'App\Http\Controllers\Backend\SliderController@store')->middleware('auth','admin')->name('slider.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\SliderController@edit')->middleware('auth','admin')->name('slider.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\SliderController@update')->middleware('auth','admin')->name('slider.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\SliderController@destroy')->middleware('auth','admin')->name('slider.destroy');

    });




     // Shipping group
    Route::group(['prefix'=>'shipping'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\ShippingController@index')->middleware('auth','admin')->name('shipping.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\ShippingController@create')->middleware('auth','admin')->name('shipping.create');
        Route::post('/store', 'App\Http\Controllers\Backend\ShippingController@store')->middleware('auth','admin')->name('shipping.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\ShippingController@edit')->middleware('auth','admin')->name('shipping.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\ShippingController@update')->middleware('auth','admin')->name('shipping.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\ShippingController@destroy')->middleware('auth','admin')->name('shipping.destroy');

    });


     // Shipping group
    Route::group(['prefix'=>'courier'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\CourierController@index')->middleware('auth','admin')->name('courier.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\CourierController@create')->middleware('auth','admin')->name('courier.create');
        Route::post('/store', 'App\Http\Controllers\Backend\CourierController@store')->middleware('auth','admin')->name('courier.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\CourierController@edit')->middleware('auth','admin')->name('courier.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\CourierController@update')->middleware('auth','admin')->name('courier.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\CourierController@destroy')->middleware('auth','admin')->name('courier.destroy');

    });


     // Shipping group
    Route::group(['prefix'=>'city'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\CityController@index')->middleware('auth','admin')->name('city.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\CityController@create')->middleware('auth','admin')->name('city.create');
        Route::post('/store', 'App\Http\Controllers\Backend\CityController@store')->middleware('auth','admin')->name('city.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\CityController@edit')->middleware('auth','admin')->name('city.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\CityController@update')->middleware('auth','admin')->name('city.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\CityController@destroy')->middleware('auth','admin')->name('city.destroy');

    });


     // Shipping group
    Route::group(['prefix'=>'zone'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\ZoneController@index')->middleware('auth','admin')->name('zone.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\ZoneController@create')->middleware('auth','admin')->name('zone.create');
        Route::post('/store', 'App\Http\Controllers\Backend\ZoneController@store')->middleware('auth','admin')->name('zone.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\ZoneController@edit')->middleware('auth','admin')->name('zone.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\ZoneController@update')->middleware('auth','admin')->name('zone.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\ZoneController@destroy')->middleware('auth','admin')->name('zone.destroy');

    });


      // product group
    Route::group(['prefix'=>'/product'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\ProductController@index')->middleware('auth','admin')->name('product.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\ProductController@create')->middleware('auth','admin')->name('product.create');
        Route::post('/store', 'App\Http\Controllers\Backend\ProductController@store')->middleware('auth','admin')->name('product.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\ProductController@edit')->middleware('auth','admin')->name('product.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\ProductController@update')->middleware('auth','admin')->name('product.update');
        Route::get('/destroy/{id}', 'App\Http\Controllers\Backend\ProductController@destroy')->middleware('auth','admin')->name('product.destroy');
        Route::get('/assign_dlt/{id}', 'App\Http\Controllers\Backend\ProductController@assign_dlt')->middleware('auth','admin')->name('assign_dlt');
        Route::get('product-export', 'App\Http\Controllers\Backend\ProductController@exportIntoExcel')->middleware('auth','admin')->name('product.export');
        Route::post('/selected-products', 'App\Http\Controllers\Backend\ProductController@deleteChecketProducts')->middleware('auth','admin')->name('deleteSelected');
        Route::post('/p-selected-status', 'App\Http\Controllers\Backend\ProductController@p_selected_status')->middleware('auth','admin')->name('p_selected_status');
        Route::get('stock', function(){
            return view('backend.pages.product.stock');
        })->name('product.stock')->middleware('auth','admin');

    });


          // landing group
    Route::group(['prefix'=>'/landing'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\ProductController@landingindex')->middleware('auth','admin')->name('landing.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\ProductController@landingcreate')->middleware('auth','admin')->name('landing.create');
        Route::post('/store', 'App\Http\Controllers\Backend\ProductController@landingstore')->middleware('auth','admin')->name('landing.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\ProductController@landingedit')->middleware('auth','admin')->name('landing.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\ProductController@landingupdate')->middleware('auth','admin')->name('landing.update');
        Route::get('/destroy/{id}', 'App\Http\Controllers\Backend\ProductController@landingdestroy')->middleware('auth','admin')->name('landing.destroy');

    });


      // user group
    Route::group(['prefix'=>'/user'],function(){
        Route::get('/manage', 'App\Http\Controllers\Backend\UserController@index')->middleware('auth','admin')->name('user.manage');
        Route::get('/create', 'App\Http\Controllers\Backend\UserController@create')->middleware('auth','admin')->name('user.create');
        Route::post('/store', 'App\Http\Controllers\Backend\UserController@store')->middleware('auth','admin')->name('user.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\UserController@edit')->middleware('auth','admin')->name('user.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\UserController@update')->middleware('auth','admin')->name('user.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\UserController@destroy')->middleware('auth','admin')->name('user.destroy');
        Route::get('user-export', 'App\Http\Controllers\Backend\UserController@exportIntoExcel')->middleware('auth','admin')->name('user.export');
        Route::post('/selected-products', 'App\Http\Controllers\Backend\UserController@deleteChecketProducts')->middleware('auth','admin')->name('deleteSelectedU');
    });


     // Order Management Route
    Route::group(['prefix'=>'/order-management'],function(){

              Route::get('/new-manage', 'App\Http\Controllers\Backend\OrderController@newIndex')->middleware('auth','admin')->name('order.newmanage');
      Route::get('/filter-data', 'App\Http\Controllers\Backend\OrderController@FilterData')->middleware('auth','admin');
      Route::get('/new-manage-action', 'App\Http\Controllers\Backend\OrderController@newIndexAction')->middleware('auth','admin');



        Route::get('/manage', 'App\Http\Controllers\Backend\OrderController@index')->middleware('auth','admin')->name('order.manage');
        Route::get('/manage/{status}', 'App\Http\Controllers\Backend\OrderController@management')->middleware('auth','admin')->name('order.management');
        Route::get('order-details/{slug}', 'App\Http\Controllers\Backend\OrderController@show')->middleware('auth','admin')->name('order.details');
        Route::get('create', 'App\Http\Controllers\Backend\OrderController@create')->middleware('auth','admin')->name('order.create');
        Route::post('store', 'App\Http\Controllers\Backend\OrderController@store')->middleware('auth','admin')->name('order.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Backend\OrderController@edit')->middleware('auth','admin')->name('order.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Backend\OrderController@update')->middleware('auth','admin')->name('order.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Backend\OrderController@destroy')->middleware('auth','admin')->name('order.destroy');

        Route::post('assign_edit/{id}', 'App\Http\Controllers\Backend\OrderController@assign_edit')->middleware('auth')->name('order.assign_edit');
        Route::post('noted_edit/{id}', 'App\Http\Controllers\Backend\OrderController@noted_edit')->middleware('auth')->name('order.noted_edit');
        Route::get('add/product','App\Http\Controllers\Backend\OrderController@addProduct')->name('add.product');

        // order status change
        Route::get('/order/{status}/{id}', 'App\Http\Controllers\Backend\OrderController@statusChange')->middleware('auth')->name('order.statusChange');

        // order export & print
        Route::get('order-export', 'App\Http\Controllers\Backend\OrderController@orderexport')->middleware('auth','admin')->name('order.export');
        Route::get('/print/{id}', 'App\Http\Controllers\Backend\OrderController@print')->middleware('auth')->name('order.print');
        Route::post('/selected-orders', 'App\Http\Controllers\Backend\OrderController@deleteChecketorders')->middleware('auth','admin')->name('deleteChecketorders');
        Route::post('/printed-orders', 'App\Http\Controllers\Backend\OrderController@printChecketorders')->middleware('auth')->name('printChecketorders');
        Route::post('/label-orders', 'App\Http\Controllers\Backend\OrderController@labelChecketorders')->middleware('auth')->name('labelChecketorders');
        Route::post('/exceled-orders', 'App\Http\Controllers\Backend\OrderController@excelChecketorders')->middleware('auth')->name('excelChecketorders');
        Route::get('/barcode-scan', 'App\Http\Controllers\Backend\OrderController@barcodeScan')->middleware('auth')->name('order.barcodeScan');
        Route::post('/scan-order', 'App\Http\Controllers\Backend\OrderController@scanOrder')->middleware('auth')->name('order.scanOrder');
        Route::post('/selected-status', 'App\Http\Controllers\Backend\OrderController@selected_status')->middleware('auth','admin')->name('selected_status');
        Route::post('/selected-e_assign', 'App\Http\Controllers\Backend\OrderController@selected_e_assign')->middleware('auth')->name('selected_e_assign');

        // order filter
        Route::get('/paginate/{count}/{status}', 'App\Http\Controllers\Backend\OrderController@paginate')->middleware('auth','admin')->name('order.paginate');
        Route::get('/search-Date/{count}', 'App\Http\Controllers\Backend\OrderController@searchByPastDate')->middleware('auth','admin')->name('order.searchByPastDate');
        Route::get('/search-Date/{count}/{status}', 'App\Http\Controllers\Backend\OrderController@searchByPastDateStatus')->middleware('auth','admin')->name('order.searchByPastDateStatus');
        Route::get('/order/searchInput', 'App\Http\Controllers\Backend\OrderController@search_order_input')->middleware('auth')->name('order.search.input');

        Route::get('/order/search', 'App\Http\Controllers\Backend\OrderController@search_order')->middleware('auth')->name('order.search');
        Route::get('/order/search/{date_from?}/{date_to?}/{status?}', 'App\Http\Controllers\Backend\OrderController@search_order_status')->middleware('auth','admin')->name('order.searchStatus');


        //optional
        Route::post('/update_s/{id}', 'App\Http\Controllers\Backend\OrderController@update_s')->middleware('auth','admin')->name('order.update_s');
        Route::post('update_auto', 'App\Http\Controllers\Backend\OrderController@update_auto')->middleware('auth','admin');


    });



});



Route::group( ['prefix'=>'employee'], function(){
    // admin dashboard page page
    Route::get('/dashboard', 'App\Http\Controllers\Employee\PagesController@dashboard')->name('employee.dashboard')->middleware('auth','employee');

     Route::get('reset', function(){
        return view('employee.pages.reset');
    })->name('employee.reset')->middleware('auth','employee');
     Route::post('r_store', 'App\Http\Controllers\Employee\PagesController@r_store')->name('employee.r_store')->middleware('auth','employee');

     // Order Management Route
    Route::group(['prefix'=>'/order-management'],function(){

        Route::get('/manage-old', 'App\Http\Controllers\Employee\OrderController@index')->middleware('auth','employee')->name('employee.order.manage');
        Route::get('/manage/{status}', 'App\Http\Controllers\Employee\OrderController@management')->middleware('auth','employee')->name('employee.order.management');

        //new update
    Route::get('/manage', 'App\Http\Controllers\Employee\OrderController@newIndex')->middleware('auth','employee')->name('employee.order.newmanage');
      Route::get('/filter-data', 'App\Http\Controllers\Employee\OrderController@FilterData')->middleware('auth','employee')->name('employee.filter-data');
      Route::get('/new-manage-action', 'App\Http\Controllers\Employee\OrderController@newIndexAction')->middleware('auth','employee')->name('employee.new-manage-action');
      Route::get('emp-total-order-list','App\Http\Controllers\Employee\OrderController@total_order_list')->name('emp_total_order_list');
      Route::get('/barcode-scan', 'App\Http\Controllers\Employee\OrderController@barcodeScan')->middleware('auth','employee')->name('employee.order.barcodeScan');
      Route::post('/scan-order', 'App\Http\Controllers\Employee\OrderController@scanOrder')->middleware('auth','employee')->name('employee.order.scanOrder');


        // status
        Route::get('order-details/{id}', 'App\Http\Controllers\Employee\OrderController@show')->middleware('auth','employee')->name('employee.order.details');
        Route::get('create', 'App\Http\Controllers\Employee\OrderController@create')->middleware('auth','employee')->name('employee.order.create');
        Route::post('store', 'App\Http\Controllers\Employee\OrderController@store')->middleware('auth','employee')->name('employee.order.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Employee\OrderController@edit')->middleware('auth','employee')->name('employee.order.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Employee\OrderController@update')->middleware('auth','employee')->name('employee.order.update');


        // order status change
        Route::get('/order/{status}/{id}', 'App\Http\Controllers\Employee\OrderController@statusChange')->middleware('auth','employee')->name('employee.order.statusChange');


       // order export & optional
        Route::post('/update_s/{id}', 'App\Http\Controllers\Employee\OrderController@update_s')->middleware('auth','employee')->name('employee.order.update_s');
        Route::post('update_auto', 'App\Http\Controllers\Employee\OrderController@update_auto')->middleware('auth','employee');
        Route::get('order-export', 'App\Http\Controllers\Employee\OrderController@orderexport')->middleware('auth','employee')->name('employee.order.export');
        Route::get('/searchInput', 'App\Http\Controllers\Employee\OrderController@search_order_input')->middleware('auth','employee')->name('order.search.input.employee');

        Route::post('noted_edit/{id}', 'App\Http\Controllers\Employee\OrderController@noted_edit')->middleware('auth','employee')->name('employee.order.noted_edit');

    });
    });












// manager start
Route::group( ['prefix'=>'manager'], function(){
    // admin dashboard page page
    Route::get('/dashboard', 'App\Http\Controllers\Manager\PagesController@dashboard')->name('manager.dashboard')->middleware('auth','manager');


    Route::get('managerreset', function(){
        return view('manager.pages.reset');
    })->name('manager.reset')->middleware('auth','manager');

    Route::post('r_store', 'App\Http\Controllers\Manager\PagesController@r_store')->name('manager.r_store')->middleware('auth','manager');



     // courier group
    Route::group(['prefix'=>'courier'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\CourierController@index')->middleware('auth','manager')->name('manager.courier.manage');
        Route::get('/create', 'App\Http\Controllers\Manager\CourierController@create')->middleware('auth','manager')->name('manager.courier.create');
        Route::post('/store', 'App\Http\Controllers\Manager\CourierController@store')->middleware('auth','manager')->name('manager.courier.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\CourierController@edit')->middleware('auth','manager')->name('manager.courier.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Manager\CourierController@update')->middleware('auth','manager')->name('manager.courier.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Manager\CourierController@destroy')->middleware('auth','manager')->name('manager.courier.destroy');

    });


     // Shipping group
    Route::group(['prefix'=>'city'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\CityController@index')->middleware('auth','manager')->name('manager.city.manage');
        Route::get('/create', 'App\Http\Controllers\Manager\CityController@create')->middleware('auth','manager')->name('manager.city.create');
        Route::post('/store', 'App\Http\Controllers\Manager\CityController@store')->middleware('auth','manager')->name('manager.city.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\CityController@edit')->middleware('auth','manager')->name('manager.city.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Manager\CityController@update')->middleware('auth','manager')->name('manager.city.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Manager\CityController@destroy')->middleware('auth','manager')->name('manager.city.destroy');

    });


     // Shipping group
    Route::group(['prefix'=>'zone'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\ZoneController@index')->middleware('auth','manager')->name('manager.zone.manage');
        Route::get('/create', 'App\Http\Controllers\Manager\ZoneController@create')->middleware('auth','manager')->name('manager.zone.create');
        Route::post('/store', 'App\Http\Controllers\Manager\ZoneController@store')->middleware('auth','manager')->name('manager.zone.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\ZoneController@edit')->middleware('auth','manager')->name('manager.zone.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Manager\ZoneController@update')->middleware('auth','manager')->name('manager.zone.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Manager\ZoneController@destroy')->middleware('auth','manager')->name('manager.zone.destroy');

    });


      // product group
    Route::group(['prefix'=>'/product'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\ProductController@index')->middleware('auth','manager')->name('manager.product.manage');
        Route::get('/create', 'App\Http\Controllers\Manager\ProductController@create')->middleware('auth','manager')->name('manager.product.create');
        Route::post('/store', 'App\Http\Controllers\Manager\ProductController@store')->middleware('auth','manager')->name('manager.product.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\ProductController@edit')->middleware('auth','manager')->name('manager.product.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Manager\ProductController@update')->middleware('auth','manager')->name('manager.product.update');
        Route::get('/destroy/{id}', 'App\Http\Controllers\Manager\ProductController@destroy')->middleware('auth','manager')->name('manager.product.destroy');

        Route::get('/assign_dlt/{id}', 'App\Http\Controllers\Manager\ProductController@assign_dlt')->middleware('auth','manager')->name('manager.assign_dlt');

         Route::get('product-export', 'App\Http\Controllers\Manager\ProductController@exportIntoExcel')->middleware('auth','manager')->name('manager.product.export');

         Route::post('/selected-products', 'App\Http\Controllers\Manager\ProductController@deleteChecketProducts')->middleware('auth','manager')->name('manager.deleteSelected');
         Route::post('/p-selected-status', 'App\Http\Controllers\Manager\ProductController@p_selected_status')->middleware('auth','manager')->name('manager.p_selected_status');




    });


      // user group
    Route::group(['prefix'=>'/user'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\UserController@index')->middleware('auth','manager')->name('manager.user.manage');
        Route::get('/create', 'App\Http\Controllers\Manager\UserController@create')->middleware('auth','manager')->name('manager.user.create');
        Route::post('/store', 'App\Http\Controllers\Manager\UserController@store')->middleware('auth','manager')->name('manager.user.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\UserController@edit')->middleware('auth','manager')->name('manager.user.edit');
        Route::post('/update/{id}', 'App\Http\Controllers\Manager\UserController@update')->middleware('auth','manager')->name('manager.user.update');
        Route::post('/destroy/{id}', 'App\Http\Controllers\Manager\UserController@destroy')->middleware('auth','manager')->name('manager.user.destroy');
         Route::get('user-export', 'App\Http\Controllers\Manager\UserController@exportIntoExcel')->middleware('auth','manager')->name('manager.user.export');

         Route::post('/selected-products', 'App\Http\Controllers\Manager\UserController@deleteChecketProducts')->middleware('auth','manager')->name('manager.deleteSelectedU');


    });


    Route::get('stock', function(){
        return view('manager.pages.product.stock');
    })->name('manager.product.stock')->middleware('auth','manager');



     // Order Management Route
    Route::group(['prefix'=>'/order-management'],function(){
        Route::get('/manage', 'App\Http\Controllers\Manager\OrderController@index')->middleware('auth','manager')->name('manager.order.manage');
        Route::get('/manage/{status}', 'App\Http\Controllers\Manager\OrderController@management')->middleware('auth','manager')->name('manager.order.management');
        Route::get('order-details/{id}', 'App\Http\Controllers\Manager\OrderController@show')->middleware('auth','manager')->name('manager.order.details');
        Route::get('create', 'App\Http\Controllers\Manager\OrderController@create')->middleware('auth','manager')->name('manager.order.create');
        Route::post('store', 'App\Http\Controllers\Manager\OrderController@store')->middleware('auth','manager')->name('manager.order.store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Manager\OrderController@edit')->middleware('auth','manager')->name('manager.order.edit');


 //new update
    Route::get('/new-manage', 'App\Http\Controllers\Manager\OrderController@newIndex')->middleware('auth','manager')->name('manager.order.newmanage');
      Route::get('/filter-data', 'App\Http\Controllers\Manager\OrderController@FilterData')->middleware('auth','manager')->name('manager.filter-data');
      Route::get('/new-manage-action', 'App\Http\Controllers\Manager\OrderController@newIndexAction')->middleware('auth','manager')->name('manager.new-manage-action');
      Route::get('manager-total-order-list','App\Http\Controllers\Manager\OrderController@total_order_list')->name('manager_total_order_list');
      Route::get('/barcode-scan', 'App\Http\Controllers\Manager\OrderController@barcodeScan')->middleware('auth','manager')->name('manager.order.barcodeScan');
      Route::post('/scan-order', 'App\Http\Controllers\Manager\OrderController@scanOrder')->middleware('auth','manager')->name('manager.order.scanOrder');



        // order status
              // order status change
        Route::get('/order/{status}/{id}', 'App\Http\Controllers\Manager\OrderController@statusChange')->middleware('auth')->name('manager.order.statusChange');



        Route::post('/update/{id}', 'App\Http\Controllers\Manager\OrderController@update')->middleware('auth','manager')->name('manager.order.update');
        Route::post('/update_s/{id}', 'App\Http\Controllers\Manager\OrderController@update_s')->middleware('auth','manager')->name('manager.order.update_s');
         Route::post('update_auto', 'App\Http\Controllers\Manager\OrderController@update_auto')->middleware('auth','manager');


        Route::post('/destroy/{id}', 'App\Http\Controllers\Manager\OrderController@destroy')->middleware('auth','manager')->name('manager.order.destroy');
          Route::get('order-export', 'App\Http\Controllers\Manager\OrderController@orderexport')->middleware('auth','manager')->name('manager.order.export');
         // Route::post('/selected-orders', 'App\Http\Controllers\Manager\ProductController@deleteChecketorders')->middleware('auth','manager')->name('manager.deleteChecketorders');
         Route::post('/selected-orders', 'App\Http\Controllers\Manager\OrderController@deleteChecketorders')->middleware('auth','manager','manager')->name('manager.deleteChecketorders');

          Route::post('/selected-status', 'App\Http\Controllers\Manager\OrderController@selected_status')->middleware('auth','manager')->name('manager.selected_status');

        Route::get('/searchInput', 'App\Http\Controllers\Manager\OrderController@search_order_input')->middleware('auth','manager')->name('order.search.input.manager');
        Route::get('/paginate/{count}/{status}', 'App\Http\Controllers\Manager\OrderController@paginate')->middleware('auth','admin')->name('order.paginate.manager');
        Route::get('/search-Date/{count}', 'App\Http\Controllers\Manager\OrderController@searchByPastDate')->middleware('auth','admin')->name('order.searchByPastDate.manager');
        Route::get('/search-Date/{count}/{status}', 'App\Http\Controllers\Manager\OrderController@searchByPastDateStatus')->middleware('auth','admin')->name('order.searchByPastDateStatus.manager');
        Route::get('/order/searchInput', 'App\Http\Controllers\Manager\OrderController@search_order_input')->middleware('auth')->name('order.search.input.manager');
        Route::get('/order/search', 'App\Http\Controllers\Manager\OrderController@search_order')->middleware('auth')->name('order.search.manager');





    });




});


// *****************************
// incomplete order
// *****************************

// routes/web.php (inside your existing /order-management group)
        Route::get('/admin/incomplete', 'App\Http\Controllers\Backend\IncompleteOrderController@index')
            ->middleware('auth')->name('order.incomplete.admin');

        Route::get('/incomplete', 'App\Http\Controllers\Backend\IncompleteOrderController@index')
         ->middleware('auth')->name('order.incomplete');

        Route::get('/incomplete/{id}', 'App\Http\Controllers\Backend\IncompleteOrderController@show')
            ->middleware('auth')->name('order.incomplete.show');

        Route::get('/incomplete/{id}/edit', 'App\Http\Controllers\Backend\IncompleteOrderController@edit')
            ->middleware('auth')->name('order.incomplete.edit');

        Route::put('/incomplete/{id}', 'App\Http\Controllers\Backend\IncompleteOrderController@update')
            ->middleware('auth')->name('order.incomplete.update');

        Route::delete('/incomplete/{id}', 'App\Http\Controllers\Backend\IncompleteOrderController@destroy')
            ->middleware('auth')->name('order.incomplete.destroy');

   // delete incomplete bulk selec
 Route::delete('/incomplete-orders/bulk-delete', 'App\Http\Controllers\Backend\IncompleteOrderController@bulkDelete')
    ->middleware('auth')
    ->name('order.incomplete.bulk-delete');

    // Convert incomplete order to completed order
    Route::post('/incomplete-orders/{id}/convert', 'App\Http\Controllers\Frontend\IncompleteOrder\IncompleteOrderController@convertToOrder')
        ->middleware('auth')
        ->name('order.incomplete.convert');

Route::post('/incomplete-order/auto-save', 'App\Http\Controllers\Frontend\IncompleteOrder\IncompleteOrderController@autoSave')
    ->name('incomplete-order.auto-save');

	// incomplete order
use App\Http\Controllers\Frontend\IncompleteOrder\IncompleteOrderController;
use App\Models\Order;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WhatsApp\WhatsAppChannel;

Route::prefix('incomplete-order')->group(function () {
    Route::post('/auto-save', [IncompleteOrderController::class, 'autoSave'])
        ->name('incomplete-order.auto-save');
});

// *********************



// end start

Route::get('/cache/clear', [App\Http\Controllers\CacheController::class, 'clear'])
    ->name('cache.clear')
    ->middleware('auth');

require __DIR__.'/auth.php';





Route::get('/notify', function(){
    Order::latest()->first()->notify(new OrderNotification('hello_world'));
    return 'Notification sent';
});
