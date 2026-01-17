<?php

use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CourierController;
use App\Http\Controllers\Backend\MarketingController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ShippingController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ZoneController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function (): void {
    Route::get('/fetch-order/{id}', [OrderController::class, 'fetch_order'])->name('fetch_order');
    Route::get('/fetch-product/{id}', [OrderController::class, 'fetch_product'])->name('fetch_product');

    // admin dashboard page
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('customers', [UserController::class, 'customerIndex'])->name('customer.manage');

    Route::get('/employee-orders', [ReportController::class, 'employee_orders'])->name('employee_orders');

    Route::get('ordered_product_c', [ReportController::class, 'products_orders'])->name('ordered_product_c');

    Route::get('/employee-orders-search', [ReportController::class, 'employee_orders_search'])->name('employee_orders_search');

    Route::post('/cart_atr_edit/{id}', [PagesController::class, 'cart_atr_edit'])->name('cart_atr_edit');

    Route::get('/employee_status/{employee?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', [ReportController::class, 'employee_status'])->name('employee_status');

    Route::get('/product_orders', [ReportController::class, 'product_orders'])->name('product_orders');

    Route::get('/product_orders_search/', [ReportController::class, 'product_orders_search'])->name('product_orders_search');

    Route::get('/product_status/{product?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', [ReportController::class, 'product_status'])->name('product_status');

    // attribute group
    Route::group(['prefix' => 'attribute'], function (): void {
        Route::get('/manage', [AttributeController::class, 'index'])->name('attribute.manage');
        Route::post('/store', [AttributeController::class, 'store'])->name('attribute.store');
        Route::post('/update/{id}', [AttributeController::class, 'update'])->name('attribute.update');
        Route::post('/destroy/{id}', [AttributeController::class, 'destroy'])->name('attribute.destroy');
        Route::post('/item_store', [AttributeController::class, 'item_store'])->name('attribute.item_store');
        Route::post('/item_update/{id}', [AttributeController::class, 'item_update'])->name('attribute.item_update');
        Route::post('/item_destroy/{id}', [AttributeController::class, 'item_destroy'])->name('attribute.item_destroy');
    });

    Route::get('customer-export', [OrderController::class, 'exportIntoExcel'])->name('customer.export');

    Route::post('p_i_e/{id}', [PagesController::class, 'p_i_e'])->name('p_i_e');
    Route::get('p_i_d/{id}', [PagesController::class, 'p_i_d'])->name('p_i_d');

    Route::post('p_g_e/{id}', [PagesController::class, 'p_g_e'])->name('p_g_e');
    Route::get('p_g_d/{id}', [PagesController::class, 'p_g_d'])->name('p_g_d');

    Route::post('p_s_e/{id}', [PagesController::class, 'p_s_e'])->name('p_s_e');
    Route::get('p_s_d/{id}', [PagesController::class, 'p_s_d'])->name('p_s_d');

    // settings group
    Route::group(['prefix' => 'settings'], function (): void {
        Route::get('/', [PagesController::class, 'edit'])->name('settings.edit');
        Route::get('/page', [PagesController::class, 'page_index'])->name('settings.web');
        Route::post('update/{id}', [PagesController::class, 'update'])->name('settings.update');
        Route::post('update/page/{id}', [PagesController::class, 'update_page'])->name('settings.update.page');
        Route::get('/pathao-api', [PagesController::class, 'pathaoIndex'])->name('settings.pathaoIndex');
        Route::post('pathao-api/update/{id}', [PagesController::class, 'pathaoUpdate'])->name('settings.pathaoUpdate');
        Route::post('steadfast-api/update/{id}', [PagesController::class, 'steadfastUpdate'])->name('settings.steadfastUpdate');
        Route::post('redxUpdate-api/update/{id}', [PagesController::class, 'redxUpdate'])->name('settings.redxUpdate');
        Route::get('/whatsapp', [PagesController::class, 'whatsappIndex'])->name('settings.whatsappIndex');
        Route::post('whatsapp/update/{id}', [PagesController::class, 'whatsappUpdate'])->name('settings.whatsappUpdate');
        Route::get('/sms', [PagesController::class, 'smsIndex'])->name('settings.smsIndex');
        Route::post('sms/update/{id}', [PagesController::class, 'smsUpdate'])->name('settings.smsUpdate');
        Route::get('/manual-order-types', [PagesController::class, 'manualOrderTypesIndex'])->name('settings.manualOrderTypesIndex');
        Route::post('manual-order-types/store', [PagesController::class, 'manualOrderTypeStore'])->name('settings.manualOrderTypeStore');
        Route::post('manual-order-types/update/{id}', [PagesController::class, 'manualOrderTypeUpdate'])->name('settings.manualOrderTypeUpdate');
        Route::delete('manual-order-types/delete/{id}', [PagesController::class, 'manualOrderTypeDestroy'])->name('settings.manualOrderTypeDestroy');
        Route::get('/order-notes', [PagesController::class, 'orderNotesIndex'])->name('settings.orderNotesIndex');
        Route::post('order-notes/store', [PagesController::class, 'orderNoteStore'])->name('settings.orderNoteStore');
        Route::post('order-notes/update/{id}', [PagesController::class, 'orderNoteUpdate'])->name('settings.orderNoteUpdate');
        Route::delete('order-notes/delete/{id}', [PagesController::class, 'orderNoteDestroy'])->name('settings.orderNoteDestroy');
    });

    Route::group(['prefix' => 'marketing'], function (): void {
        Route::get('/', [MarketingController::class, 'index'])->name('marketing.index');
        Route::get('/filter', [MarketingController::class, 'filter'])->name('marketing.filter');
        Route::post('/send', [MarketingController::class, 'sendBulkSms'])->name('marketing.send');
    });

    Route::get('/user_products', fn () => view('backend.pages.user_products'))->name('user_products');

    Route::get('reset', fn () => view('backend.pages.reset'))->name('admin.reset');

    Route::post('r_store', [PagesController::class, 'r_store'])->name('admin.r_store');

    // category group
    Route::group(['prefix' => '/category'], function (): void {
        Route::get('/manage', [CategoryController::class, 'index'])->name('category.manage');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    Route::group(['prefix' => '/subcategory'], function (): void {
        Route::get('/manage', [CategoryController::class, 'sub_index'])->name('subcategory.manage');
        Route::get('/create', [CategoryController::class, 'sub_create'])->name('subcategory.create');
        Route::post('/store', [CategoryController::class, 'sub_store'])->name('subcategory.store');
        Route::get('/edit/{id}', [CategoryController::class, 'sub_edit'])->name('subcategory.edit');
        Route::post('/update/{id}', [CategoryController::class, 'sub_update'])->name('subcategory.update');
        Route::post('/destroy/{id}', [CategoryController::class, 'sub_destroy'])->name('subcategory.destroy');
    });

    Route::group(['prefix' => '/childcategory'], function (): void {
        Route::get('/manage', [CategoryController::class, 'child_index'])->name('childcategory.manage');
        Route::get('/create', [CategoryController::class, 'child_create'])->name('childcategory.create');
        Route::post('/store', [CategoryController::class, 'child_store'])->name('childcategory.store');
        Route::get('/edit/{id}', [CategoryController::class, 'child_edit'])->name('childcategory.edit');
        Route::post('/update/{id}', [CategoryController::class, 'child_update'])->name('childcategory.update');
        Route::post('/destroy/{id}', [CategoryController::class, 'child_destroy'])->name('childcategory.destroy');
    });

    // brand group
    Route::group(['prefix' => '/brand'], function (): void {
        Route::get('/manage', [CategoryController::class, 'indexBrand'])->name('brand.manage');
        Route::get('/create', [CategoryController::class, 'createBrand'])->name('brand.create');
        Route::post('/store', [CategoryController::class, 'storeBrand'])->name('brand.store');
        Route::get('/edit/{id}', [CategoryController::class, 'editBrand'])->name('brand.edit');
        Route::post('/update/{id}', [CategoryController::class, 'updateBrand'])->name('brand.update');
        Route::post('/destroy/{id}', [CategoryController::class, 'destroyBrand'])->name('brand.destroy');
    });

    // slider group
    Route::group(['prefix' => '/slider'], function (): void {
        Route::get('/manage', [SliderController::class, 'index'])->name('slider.manage');
        Route::get('/create', [SliderController::class, 'create'])->name('slider.create');
        Route::post('/store', [SliderController::class, 'store'])->name('slider.store');
        Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
        Route::post('/update/{id}', [SliderController::class, 'update'])->name('slider.update');
        Route::post('/destroy/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    });

    // Shipping group
    Route::group(['prefix' => 'shipping'], function (): void {
        Route::get('/manage', [ShippingController::class, 'index'])->name('shipping.manage');
        Route::get('/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/store', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::post('/update/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::post('/destroy/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');
    });

    // Courier group
    Route::group(['prefix' => 'courier'], function (): void {
        Route::get('/manage', [CourierController::class, 'index'])->name('courier.manage');
        Route::get('/create', [CourierController::class, 'create'])->name('courier.create');
        Route::post('/store', [CourierController::class, 'store'])->name('courier.store');
        Route::get('/edit/{id}', [CourierController::class, 'edit'])->name('courier.edit');
        Route::post('/update/{id}', [CourierController::class, 'update'])->name('courier.update');
        Route::post('/destroy/{id}', [CourierController::class, 'destroy'])->name('courier.destroy');
    });

    // City group
    Route::group(['prefix' => 'city'], function (): void {
        Route::get('/manage', [CityController::class, 'index'])->name('city.manage');
        Route::get('/create', [CityController::class, 'create'])->name('city.create');
        Route::post('/store', [CityController::class, 'store'])->name('city.store');
        Route::get('/edit/{id}', [CityController::class, 'edit'])->name('city.edit');
        Route::post('/update/{id}', [CityController::class, 'update'])->name('city.update');
        Route::post('/destroy/{id}', [CityController::class, 'destroy'])->name('city.destroy');
    });

    // Zone group
    Route::group(['prefix' => 'zone'], function (): void {
        Route::get('/manage', [ZoneController::class, 'index'])->name('zone.manage');
        Route::get('/create', [ZoneController::class, 'create'])->name('zone.create');
        Route::post('/store', [ZoneController::class, 'store'])->name('zone.store');
        Route::get('/edit/{id}', [ZoneController::class, 'edit'])->name('zone.edit');
        Route::post('/update/{id}', [ZoneController::class, 'update'])->name('zone.update');
        Route::post('/destroy/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');
    });

    // product group
    Route::group(['prefix' => '/product'], function (): void {
        Route::get('/manage', [ProductController::class, 'index'])->name('product.manage');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::get('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/assign_dlt/{id}', [ProductController::class, 'assign_dlt'])->name('assign_dlt');
        Route::get('product-export', [ProductController::class, 'exportIntoExcel'])->name('product.export');
        Route::post('/selected-products', [ProductController::class, 'deleteChecketProducts'])->name('deleteSelected');
        Route::post('/p-selected-status', [ProductController::class, 'p_selected_status'])->name('p_selected_status');
        Route::get('stock', fn () => view('backend.pages.product.stock'))->name('product.stock');
    });

    // landing group
    Route::group(['prefix' => '/landing'], function (): void {
        Route::get('/manage', [ProductController::class, 'landingindex'])->name('landing.manage');
        Route::get('/create', [ProductController::class, 'landingcreate'])->name('landing.create');
        Route::post('/store', [ProductController::class, 'landingstore'])->name('landing.store');
        Route::get('/edit/{id}', [ProductController::class, 'landingedit'])->name('landing.edit');
        Route::post('/update/{id}', [ProductController::class, 'landingupdate'])->name('landing.update');
        Route::get('/destroy/{id}', [ProductController::class, 'landingdestroy'])->name('landing.destroy');
    });

    // user group
    Route::group(['prefix' => '/user'], function (): void {
        Route::get('/manage', [UserController::class, 'index'])->name('user.manage');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('user-export', [UserController::class, 'exportIntoExcel'])->name('user.export');
        Route::post('/selected-products', [UserController::class, 'deleteChecketProducts'])->name('deleteSelectedU');
    });

    // Order Management Route
    Route::group(['prefix' => '/order-management'], function (): void {
        Route::get('/new-manage', [OrderController::class, 'newIndex'])->name('order.newmanage');
        Route::get('/filter-data', [OrderController::class, 'FilterData']);
        Route::get('/new-manage-action', [OrderController::class, 'newIndexAction']);

        Route::get('/manage', [OrderController::class, 'index'])->name('order.manage');
        Route::get('/manage/{status}', [OrderController::class, 'management'])->name('order.management');
        Route::get('order-details/{slug}', [OrderController::class, 'show'])->name('order.details');
        Route::get('create', [OrderController::class, 'create'])->name('order.create');
        Route::post('store', [OrderController::class, 'store'])->name('order.store');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('order.update');
        Route::post('/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');

        Route::post('assign_edit/{id}', [OrderController::class, 'assign_edit'])->middleware('auth')->name('order.assign_edit');
        Route::post('noted_edit/{id}', [OrderController::class, 'noted_edit'])->middleware('auth')->name('order.noted_edit');
        Route::get('add/product', [OrderController::class, 'addProduct'])->name('add.product');

        // order status change
        Route::get('/order/{status}/{id}', [OrderController::class, 'statusChange'])->middleware('auth')->name('order.statusChange');

        // order export & print
        Route::get('order-export', [OrderController::class, 'orderexport'])->name('order.export');
        Route::get('/print/{id}', [OrderController::class, 'print'])->middleware('auth')->name('order.print');
        Route::post('/selected-orders', [OrderController::class, 'deleteChecketorders'])->name('deleteChecketorders');
        Route::post('/printed-orders', [OrderController::class, 'printChecketorders'])->middleware('auth')->name('printChecketorders');
        Route::post('/label-orders', [OrderController::class, 'labelChecketorders'])->middleware('auth')->name('labelChecketorders');
        Route::post('/exceled-orders', [OrderController::class, 'excelChecketorders'])->middleware('auth')->name('excelChecketorders');
        Route::get('/parcel-handover', [OrderController::class, 'parcelHandover'])->middleware('auth')->name('order.parcelHandover');
        Route::post('/scan-parcel-handover', [OrderController::class, 'scanParcelHandover'])->middleware('auth')->name('order.scanParcelHandover');
        Route::get('/return-received', [OrderController::class, 'returnReceived'])->middleware('auth')->name('order.returnReceived');
        Route::post('/scan-return-received', [OrderController::class, 'scanReturnReceived'])->middleware('auth')->name('order.scanReturnReceived');
        Route::get('/get-scanned-orders', [OrderController::class, 'getScannedOrders'])->middleware('auth')->name('order.getScannedOrders');
        Route::get('/print-scanned-orders', [OrderController::class, 'printScannedOrders'])->middleware('auth')->name('order.printScannedOrders');
        Route::post('/delete-scanned-order', [OrderController::class, 'deleteScannedOrder'])->middleware('auth')->name('order.deleteScannedOrder');
        Route::post('/selected-status', [OrderController::class, 'selected_status'])->name('selected_status');
        Route::post('/selected-e_assign', [OrderController::class, 'selected_e_assign'])->middleware('auth')->name('selected_e_assign');

        // order filter
        Route::get('/paginate/{count}/{status}', [OrderController::class, 'paginate'])->name('order.paginate');
        Route::get('/search-Date/{count}', [OrderController::class, 'searchByPastDate'])->name('order.searchByPastDate');
        Route::get('/search-Date/{count}/{status}', [OrderController::class, 'searchByPastDateStatus'])->name('order.searchByPastDateStatus');
        Route::get('/order/searchInput', [OrderController::class, 'search_order_input'])->middleware('auth')->name('order.search.input');
        Route::get('/order/search', [OrderController::class, 'search_order'])->middleware('auth')->name('order.search');
        Route::get('/order/search/{date_from?}/{date_to?}/{status?}', [OrderController::class, 'search_order_status'])->name('order.searchStatus');

        // optional
        Route::post('/update_s/{id}', [OrderController::class, 'update_s'])->name('order.update_s');
        Route::post('update_auto', [OrderController::class, 'update_auto']);
    });
});
