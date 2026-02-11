<?php

use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\CourierController;
use App\Http\Controllers\Backend\MarketingController;
use App\Http\Controllers\Backend\MonthlyPayrollController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\PayrollSettingController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\SalaryAdvanceController;
use App\Http\Controllers\Backend\ShippingController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ZoneController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function (): void {
    Route::get('/fetch-order/{id}', [OrderController::class, 'fetch_order'])->name('fetch_order')->middleware('auth', 'admin');
    Route::get('/fetch-product/{id}', [OrderController::class, 'fetch_product'])->name('fetch_product')->middleware('auth', 'admin');
    Route::post('/orders/{order}/forwarding/retry', [OrderController::class, 'forwardingRetry'])->name('orders.forwarding.retry')->middleware('auth', 'admin');

    // admin dashboard page
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth', 'admin');

    Route::get('customers', [UserController::class, 'customerIndex'])->middleware('auth', 'admin')->name('customer.manage');

    Route::get('/employee-orders', [ReportController::class, 'employee_orders'])->name('employee_orders')->middleware('auth', 'admin');

    Route::get('ordered_product_c', [ReportController::class, 'products_orders'])->name('ordered_product_c')->middleware('auth', 'admin');

    Route::get('/employee-orders-search', [ReportController::class, 'employee_orders_search'])->name('employee_orders_search')->middleware('auth', 'admin');

    Route::post('/cart_atr_edit/{id}', [PagesController::class, 'cart_atr_edit'])->name('cart_atr_edit')->middleware('auth', 'admin');

    Route::get('/employee_status/{employee?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', [ReportController::class, 'employee_status'])->name('employee_status')->middleware('auth', 'admin');

    Route::get('/product_orders', [ReportController::class, 'product_orders'])->name('product_orders')->middleware('auth', 'admin');

    Route::get('/product_orders_search/', [ReportController::class, 'product_orders_search'])->name('product_orders_search')->middleware('auth', 'admin');

    Route::get('/product_status/{product?}/{status?}/{searchDays?}/{fromDate?}/{toDate?}', [ReportController::class, 'product_status'])->name('product_status')->middleware('auth', 'admin');

    // attribute group
    Route::group(['prefix' => 'attribute'], function (): void {
        Route::get('/manage', [AttributeController::class, 'index'])->name('attribute.manage')->middleware('auth', 'admin');
        Route::post('/store', [AttributeController::class, 'store'])->name('attribute.store')->middleware('auth', 'admin');
        Route::post('/update/{id}', [AttributeController::class, 'update'])->name('attribute.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [AttributeController::class, 'destroy'])->name('attribute.destroy')->middleware('auth', 'admin');
        Route::post('/item_store', [AttributeController::class, 'item_store'])->name('attribute.item_store')->middleware('auth', 'admin');
        Route::post('/item_update/{id}', [AttributeController::class, 'item_update'])->name('attribute.item_update')->middleware('auth', 'admin');
        Route::post('/item_destroy/{id}', [AttributeController::class, 'item_destroy'])->name('attribute.item_destroy')->middleware('auth', 'admin');
    });

    Route::get('customer-export', [OrderController::class, 'exportIntoExcel'])->name('customer.export')->middleware('auth', 'admin');

    Route::post('p_i_e/{id}', [PagesController::class, 'p_i_e'])->name('p_i_e')->middleware('auth', 'admin');
    Route::get('p_i_d/{id}', [PagesController::class, 'p_i_d'])->name('p_i_d')->middleware('auth', 'admin');

    Route::post('p_g_e/{id}', [PagesController::class, 'p_g_e'])->name('p_g_e')->middleware('auth', 'admin');
    Route::get('p_g_d/{id}', [PagesController::class, 'p_g_d'])->name('p_g_d')->middleware('auth', 'admin');

    Route::post('p_s_e/{id}', [PagesController::class, 'p_s_e'])->name('p_s_e')->middleware('auth', 'admin');
    Route::get('p_s_d/{id}', [PagesController::class, 'p_s_d'])->name('p_s_d')->middleware('auth', 'admin');

    // settings group
    Route::group(['prefix' => 'settings'], function (): void {
        Route::get('/', [PagesController::class, 'edit'])->name('settings.edit')->middleware('auth', 'admin');
        Route::get('/page', [PagesController::class, 'page_index'])->name('settings.web')->middleware('auth', 'admin');
        Route::post('update/{id}', [PagesController::class, 'update'])->name('settings.update')->middleware('auth', 'admin');
        Route::post('update/page/{id}', [PagesController::class, 'update_page'])->name('settings.update.page')->middleware('auth', 'admin');
        Route::get('/pathao-api', [PagesController::class, 'pathaoIndex'])->name('settings.pathaoIndex')->middleware('auth', 'admin');
        Route::post('pathao-api/update/{id}', [PagesController::class, 'pathaoUpdate'])->name('settings.pathaoUpdate')->middleware('auth', 'admin');
        Route::post('steadfast-api/update/{id}', [PagesController::class, 'steadfastUpdate'])->name('settings.steadfastUpdate')->middleware('auth', 'admin');
        Route::post('redxUpdate-api/update/{id}', [PagesController::class, 'redxUpdate'])->name('settings.redxUpdate')->middleware('auth', 'admin');
        Route::get('/whatsapp', [PagesController::class, 'whatsappIndex'])->name('settings.whatsappIndex')->middleware('auth', 'admin');
        Route::post('whatsapp/update/{id}', [PagesController::class, 'whatsappUpdate'])->name('settings.whatsappUpdate')->middleware('auth', 'admin');
        Route::get('/sms', [PagesController::class, 'smsIndex'])->name('settings.smsIndex')->middleware('auth', 'admin');
        Route::post('sms/update/{id}', [PagesController::class, 'smsUpdate'])->name('settings.smsUpdate')->middleware('auth', 'admin');
        Route::get('/manual-order-types', [PagesController::class, 'manualOrderTypesIndex'])->name('settings.manualOrderTypesIndex')->middleware('auth', 'admin');
        Route::post('manual-order-types/store', [PagesController::class, 'manualOrderTypeStore'])->name('settings.manualOrderTypeStore')->middleware('auth', 'admin');
        Route::post('manual-order-types/update/{id}', [PagesController::class, 'manualOrderTypeUpdate'])->name('settings.manualOrderTypeUpdate')->middleware('auth', 'admin');
        Route::delete('manual-order-types/delete/{id}', [PagesController::class, 'manualOrderTypeDestroy'])->name('settings.manualOrderTypeDestroy')->middleware('auth', 'admin');
        Route::get('/order-notes', [PagesController::class, 'orderNotesIndex'])->name('settings.orderNotesIndex')->middleware('auth', 'admin');
        Route::post('order-notes/store', [PagesController::class, 'orderNoteStore'])->name('settings.orderNoteStore')->middleware('auth', 'admin');
        Route::post('order-notes/update/{id}', [PagesController::class, 'orderNoteUpdate'])->name('settings.orderNoteUpdate')->middleware('auth', 'admin');
        Route::delete('order-notes/delete/{id}', [PagesController::class, 'orderNoteDestroy'])->name('settings.orderNoteDestroy')->middleware('auth', 'admin');
    });

    Route::group(['prefix' => 'marketing'], function (): void {
        Route::get('/', [MarketingController::class, 'index'])->name('marketing.index')->middleware('auth', 'admin');
        Route::get('/filter', [MarketingController::class, 'filter'])->name('marketing.filter')->middleware('auth', 'admin');
        Route::post('/send', [MarketingController::class, 'sendBulkSms'])->name('marketing.send')->middleware('auth', 'admin');
    });

    Route::get('/user_products', fn () => view('backend.pages.user_products'))->name('user_products')->middleware('auth', 'admin');

    Route::get('reset', fn () => view('backend.pages.reset'))->name('admin.reset')->middleware('auth', 'admin');

    Route::post('r_store', [PagesController::class, 'r_store'])->name('admin.r_store')->middleware('auth', 'admin');

    // category group
    Route::group(['prefix' => '/category'], function (): void {
        Route::get('/manage', [CategoryController::class, 'index'])->name('category.manage')->middleware('auth', 'admin');
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create')->middleware('auth', 'admin');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy')->middleware('auth', 'admin');
    });

    Route::group(['prefix' => '/subcategory'], function (): void {
        Route::get('/manage', [CategoryController::class, 'sub_index'])->name('subcategory.manage')->middleware('auth', 'admin');
        Route::get('/create', [CategoryController::class, 'sub_create'])->name('subcategory.create')->middleware('auth', 'admin');
        Route::post('/store', [CategoryController::class, 'sub_store'])->name('subcategory.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CategoryController::class, 'sub_edit'])->name('subcategory.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CategoryController::class, 'sub_update'])->name('subcategory.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CategoryController::class, 'sub_destroy'])->name('subcategory.destroy')->middleware('auth', 'admin');
    });

    Route::group(['prefix' => '/childcategory'], function (): void {
        Route::get('/manage', [CategoryController::class, 'child_index'])->name('childcategory.manage')->middleware('auth', 'admin');
        Route::get('/create', [CategoryController::class, 'child_create'])->name('childcategory.create')->middleware('auth', 'admin');
        Route::post('/store', [CategoryController::class, 'child_store'])->name('childcategory.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CategoryController::class, 'child_edit'])->name('childcategory.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CategoryController::class, 'child_update'])->name('childcategory.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CategoryController::class, 'child_destroy'])->name('childcategory.destroy')->middleware('auth', 'admin');
    });

    // brand group
    Route::group(['prefix' => '/brand'], function (): void {
        Route::get('/manage', [CategoryController::class, 'indexBrand'])->name('brand.manage')->middleware('auth', 'admin');
        Route::get('/create', [CategoryController::class, 'createBrand'])->name('brand.create')->middleware('auth', 'admin');
        Route::post('/store', [CategoryController::class, 'storeBrand'])->name('brand.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CategoryController::class, 'editBrand'])->name('brand.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CategoryController::class, 'updateBrand'])->name('brand.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CategoryController::class, 'destroyBrand'])->name('brand.destroy')->middleware('auth', 'admin');
    });

    // slider group
    Route::group(['prefix' => '/slider'], function (): void {
        Route::get('/manage', [SliderController::class, 'index'])->name('slider.manage')->middleware('auth', 'admin');
        Route::get('/create', [SliderController::class, 'create'])->name('slider.create')->middleware('auth', 'admin');
        Route::post('/store', [SliderController::class, 'store'])->name('slider.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [SliderController::class, 'update'])->name('slider.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [SliderController::class, 'destroy'])->name('slider.destroy')->middleware('auth', 'admin');
    });

    // Shipping group
    Route::group(['prefix' => 'shipping'], function (): void {
        Route::get('/manage', [ShippingController::class, 'index'])->name('shipping.manage')->middleware('auth', 'admin');
        Route::get('/create', [ShippingController::class, 'create'])->name('shipping.create')->middleware('auth', 'admin');
        Route::post('/store', [ShippingController::class, 'store'])->name('shipping.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [ShippingController::class, 'update'])->name('shipping.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy')->middleware('auth', 'admin');
    });

    // Courier group
    Route::group(['prefix' => 'courier'], function (): void {
        Route::get('/manage', [CourierController::class, 'index'])->name('courier.manage')->middleware('auth', 'admin');
        Route::get('/create', [CourierController::class, 'create'])->name('courier.create')->middleware('auth', 'admin');
        Route::post('/store', [CourierController::class, 'store'])->name('courier.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CourierController::class, 'edit'])->name('courier.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CourierController::class, 'update'])->name('courier.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CourierController::class, 'destroy'])->name('courier.destroy')->middleware('auth', 'admin');
    });

    // City group
    Route::group(['prefix' => 'city'], function (): void {
        Route::get('/manage', [CityController::class, 'index'])->name('city.manage')->middleware('auth', 'admin');
        Route::get('/create', [CityController::class, 'create'])->name('city.create')->middleware('auth', 'admin');
        Route::post('/store', [CityController::class, 'store'])->name('city.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [CityController::class, 'edit'])->name('city.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [CityController::class, 'update'])->name('city.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [CityController::class, 'destroy'])->name('city.destroy')->middleware('auth', 'admin');
    });

    // Zone group
    Route::group(['prefix' => 'zone'], function (): void {
        Route::get('/manage', [ZoneController::class, 'index'])->name('zone.manage')->middleware('auth', 'admin');
        Route::get('/create', [ZoneController::class, 'create'])->name('zone.create')->middleware('auth', 'admin');
        Route::post('/store', [ZoneController::class, 'store'])->name('zone.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [ZoneController::class, 'edit'])->name('zone.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [ZoneController::class, 'update'])->name('zone.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy')->middleware('auth', 'admin');
    });

    // product group
    Route::group(['prefix' => '/product'], function (): void {
        Route::get('/manage', [ProductController::class, 'index'])->name('product.manage')->middleware('auth', 'admin');
        Route::get('/create', [ProductController::class, 'create'])->name('product.create')->middleware('auth', 'admin');
        Route::post('/store', [ProductController::class, 'store'])->name('product.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update')->middleware('auth', 'admin');
        Route::get('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('auth', 'admin');
        Route::get('/assign_dlt/{id}', [ProductController::class, 'assign_dlt'])->name('assign_dlt')->middleware('auth', 'admin');
        Route::get('product-export', [ProductController::class, 'exportIntoExcel'])->name('product.export')->middleware('auth', 'admin');
        Route::post('/selected-products', [ProductController::class, 'deleteChecketProducts'])->name('deleteSelected')->middleware('auth', 'admin');
        Route::post('/p-selected-status', [ProductController::class, 'p_selected_status'])->name('p_selected_status')->middleware('auth', 'admin');
        Route::get('stock', fn () => view('backend.pages.product.stock'))->name('product.stock')->middleware('auth', 'admin');
    });

    // landing group
    Route::group(['prefix' => '/landing'], function (): void {
        Route::get('/manage', [ProductController::class, 'landingindex'])->name('landing.manage')->middleware('auth', 'admin');
        Route::get('/create', [ProductController::class, 'landingcreate'])->name('landing.create')->middleware('auth', 'admin');
        Route::post('/store', [ProductController::class, 'landingstore'])->name('landing.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [ProductController::class, 'landingedit'])->name('landing.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [ProductController::class, 'landingupdate'])->name('landing.update')->middleware('auth', 'admin');
        Route::get('/destroy/{id}', [ProductController::class, 'landingdestroy'])->name('landing.destroy')->middleware('auth', 'admin');
    });

    // user group
    Route::group(['prefix' => '/user'], function (): void {
        Route::get('/manage', [UserController::class, 'index'])->name('user.manage')->middleware('auth', 'admin');
        Route::get('/create', [UserController::class, 'create'])->name('user.create')->middleware('auth', 'admin');
        Route::post('/store', [UserController::class, 'store'])->name('user.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('auth', 'admin');
        Route::get('user-export', [UserController::class, 'exportIntoExcel'])->name('user.export')->middleware('auth', 'admin');
        Route::post('/selected-products', [UserController::class, 'deleteChecketProducts'])->name('deleteSelectedU')->middleware('auth', 'admin');
    });

    // Order Management Route
    Route::group(['prefix' => '/order-management'], function (): void {
        Route::get('/new-manage', [OrderController::class, 'newIndex'])->name('order.newmanage')->middleware('auth', 'admin');
        Route::get('/filter-data', [OrderController::class, 'FilterData'])->middleware('auth', 'admin');
        Route::get('/new-manage-action', [OrderController::class, 'newIndexAction'])->middleware('auth', 'admin');

        Route::get('/manage', [OrderController::class, 'index'])->name('order.manage')->middleware('auth', 'admin');
        Route::get('/manage/{status}', [OrderController::class, 'management'])->name('order.management')->middleware('auth', 'admin');
        Route::get('order-details/{slug}', [OrderController::class, 'show'])->name('order.details')->middleware('auth', 'admin');
        Route::get('create', [OrderController::class, 'create'])->name('order.create')->middleware('auth', 'admin');
        Route::post('store', [OrderController::class, 'store'])->name('order.store')->middleware('auth', 'admin');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('order.edit')->middleware('auth', 'admin');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('order.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy')->middleware('auth', 'admin');

        Route::post('assign_edit/{id}', [OrderController::class, 'assign_edit'])->middleware('auth')->name('order.assign_edit');
        Route::post('noted_edit/{id}', [OrderController::class, 'noted_edit'])->middleware('auth')->name('order.noted_edit');
        Route::get('add/product', [OrderController::class, 'addProduct'])->name('add.product');

        // order status change
        Route::get('/order/{status}/{id}', [OrderController::class, 'statusChange'])->middleware('auth')->name('order.statusChange');

        // order export & print
        Route::get('order-export', [OrderController::class, 'orderexport'])->name('order.export')->middleware('auth', 'admin');
        Route::get('/print/{id}', [OrderController::class, 'print'])->middleware('auth')->name('order.print');
        Route::post('/selected-orders', [OrderController::class, 'deleteChecketorders'])->name('deleteChecketorders')->middleware('auth', 'admin');
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
        Route::post('/selected-status', [OrderController::class, 'selected_status'])->name('selected_status')->middleware('auth', 'admin');
        Route::post('/selected-e_assign', [OrderController::class, 'selected_e_assign'])->middleware('auth')->name('selected_e_assign');

        // order filter
        Route::get('/paginate/{count}/{status}', [OrderController::class, 'paginate'])->name('order.paginate')->middleware('auth', 'admin');
        Route::get('/search-Date/{count}', [OrderController::class, 'searchByPastDate'])->name('order.searchByPastDate')->middleware('auth', 'admin');
        Route::get('/search-Date/{count}/{status}', [OrderController::class, 'searchByPastDateStatus'])->name('order.searchByPastDateStatus')->middleware('auth', 'admin');
        Route::get('/order/searchInput', [OrderController::class, 'search_order_input'])->middleware('auth')->name('order.search.input');
        Route::get('/order/search', [OrderController::class, 'search_order'])->middleware('auth')->name('order.search');
        Route::get('/order/search/{date_from?}/{date_to?}/{status?}', [OrderController::class, 'search_order_status'])->name('order.searchStatus')->middleware('auth', 'admin');

        // optional
        Route::post('/update_s/{id}', [OrderController::class, 'update_s'])->name('order.update_s')->middleware('auth', 'admin');
        Route::post('update_auto', [OrderController::class, 'update_auto'])->middleware('auth', 'admin');
    });

    // Attendance Management
    Route::group(['prefix' => 'attendance'], function (): void {
        Route::get('/', [AttendanceController::class, 'index'])->name('admin.attendance.index')->middleware('auth', 'admin');
        Route::get('/history', [AttendanceController::class, 'history'])->name('admin.attendance.history')->middleware('auth', 'admin');
        Route::post('/store', [AttendanceController::class, 'store'])->name('admin.attendance.store')->middleware('auth', 'admin');
        Route::post('/check-in', [AttendanceController::class, 'manualCheckIn'])->name('admin.attendance.checkIn')->middleware('auth', 'admin');
        Route::post('/check-out', [AttendanceController::class, 'manualCheckOut'])->name('admin.attendance.checkOut')->middleware('auth', 'admin');
        Route::post('/mark-absent', [AttendanceController::class, 'markAbsent'])->name('admin.attendance.markAbsent')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [AttendanceController::class, 'destroy'])->name('admin.attendance.destroy')->middleware('auth', 'admin');
        Route::post('/update', [AttendanceController::class, 'update'])->name('admin.attendance.update')->middleware('auth', 'admin');
        // Self-service
        Route::post('/toggle', [AttendanceController::class, 'selfToggle'])->name('admin.attendance.toggle')->middleware('auth', 'admin');
        Route::get('/self-status', [AttendanceController::class, 'selfStatus'])->name('admin.attendance.selfStatus')->middleware('auth', 'admin');
        Route::get('/my', [AttendanceController::class, 'myAttendance'])->name('admin.attendance.my')->middleware('auth', 'admin');
    });

    // Payroll Management
    Route::group(['prefix' => 'payroll'], function (): void {
        Route::get('/settings', [PayrollSettingController::class, 'index'])->name('admin.payroll.settings')->middleware('auth', 'admin');
        Route::post('/settings/update', [PayrollSettingController::class, 'update'])->name('admin.payroll.settings.update')->middleware('auth', 'admin');
        Route::get('/monthly', [MonthlyPayrollController::class, 'index'])->name('admin.payroll.monthly')->middleware('auth', 'admin');
        Route::post('/generate', [MonthlyPayrollController::class, 'generate'])->name('admin.payroll.generate')->middleware('auth', 'admin');
        Route::post('/generate-single', [MonthlyPayrollController::class, 'generateSingle'])->name('admin.payroll.generateSingle')->middleware('auth', 'admin');
        Route::get('/show/{id}', [MonthlyPayrollController::class, 'show'])->name('admin.payroll.show')->middleware('auth', 'admin');
        Route::post('/update-status/{id}', [MonthlyPayrollController::class, 'updateStatus'])->name('admin.payroll.updateStatus')->middleware('auth', 'admin');
        // Self-service
        Route::get('/my', [MonthlyPayrollController::class, 'myPayrolls'])->name('admin.payroll.my')->middleware('auth', 'admin');
        Route::get('/my/{id}', [MonthlyPayrollController::class, 'myPayrollShow'])->name('admin.payroll.myShow')->middleware('auth', 'admin');
        Route::get('/my-advances', [MonthlyPayrollController::class, 'myAdvances'])->name('admin.payroll.myAdvances')->middleware('auth', 'admin');
    });

    // Salary Advance Management
    Route::group(['prefix' => 'salary-advance'], function (): void {
        Route::get('/', [SalaryAdvanceController::class, 'index'])->name('admin.salary-advance.index')->middleware('auth', 'admin');
        Route::post('/store', [SalaryAdvanceController::class, 'store'])->name('admin.salary-advance.store')->middleware('auth', 'admin');
        Route::post('/update/{id}', [SalaryAdvanceController::class, 'update'])->name('admin.salary-advance.update')->middleware('auth', 'admin');
        Route::post('/destroy/{id}', [SalaryAdvanceController::class, 'destroy'])->name('admin.salary-advance.destroy')->middleware('auth', 'admin');
    });
});
