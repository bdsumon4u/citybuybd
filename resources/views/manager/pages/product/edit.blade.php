@extends('manager.layout.template')
@section('body-content')
    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('manager.product.update', $product->id) }}" enctype="multipart/form-data"
                            method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <label class="col-sm-3 form-control-label">Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="category_id" id="category_id" required="required"
                                                class="form-control">
                                                <option value="">Please select Category</option>
                                                @foreach (App\Models\Category::all() as $category)
                                                    <option value="{{ $category->id }}"
                                                        @if ($category->id == $product->category_id) selected @endif>
                                                        {{ $category->title }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>



                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Sub Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="subcategory_id" id="subcategory_id" class="form-control">
                                                <option value="{{ $subcategory->id ?? '' }}">{{ $subcategory->title ?? '' }}
                                                </option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Child Category Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="childcategory_id" id="childcategory_id" class="form-control">
                                                <option value="{{ $childcategory->id ?? '' }}">
                                                    {{ $childcategory->title ?? '' }} </option>

                                            </select>
                                        </div>
                                    </div>




                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Brand Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <select name="brand_id" class="form-control">
                                                <option value="">Please select Brand</option>
                                                @foreach (App\Models\Brand::all() as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        @if ($brand->id == $product->brand_id) selected @endif>
                                                        {{ $brand->title }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">product Name*</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="name" class="form-control" autocomplete="off"
                                                required="required" value="{{ $product->name }}"
                                                placeholder="Enter product Name">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">SKU </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="sku" value="{{ $product->sku }}"
                                                class="form-control" autocomplete="off" placeholder="Enter SKU code">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Serial </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="text" name="serial" value="{{ $product->serial }}"
                                                class="form-control" autocomplete="off" placeholder="Enter serial">
                                        </div>
                                    </div>


                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Regular Price* </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{ $product->regular_price }}"
                                                name="regular_price" class="form-control" autocomplete="off"
                                                required="required" placeholder="Enter regular price">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Offer Price </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{ $product->offer_price }}" name="offer_price"
                                                class="form-control" autocomplete="off" placeholder="Enter offer price">
                                        </div>
                                    </div>
                                    <div class="row mt-3 bg-light p-2 rounded border mx-0 mb-3">
                                        <div class="col-12 mb-2">
                                            <label class="form-control-label font-weight-bold text-dark mb-0">
                                                <i class="fa fa-cubes text-info"></i> Product Type / Bundle Setup
                                            </label>
                                            <p class="text-muted tx-12 mb-0">Search and select a base product if this product is a bundle/combo pack (e.g. 2 Pcs, 3 Pcs pack).</p>
                                        </div>
                                        <div class="col-sm-7 mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="tx-12 font-weight-bold text-secondary mb-0">Base Product (Searchable)</label>
                                                <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="remove_base_product_btn" style="{{ $product->base_id ? '' : 'display: none;' }}" title="Convert to Independent Base Product">
                                                    <i class="fa fa-times-circle"></i> Remove Base Product
                                                </button>
                                            </div>
                                            <select name="base_id" id="base_id_select" class="form-control select2" style="width: 100%;">
                                                <option value="0" {{ (empty(old('base_id', $product->base_id)) || old('base_id', $product->base_id) == '0') ? 'selected' : '' }}>— None (Independent Base Product) —</option>
                                                @if(isset($baseProducts))
                                                    @foreach($baseProducts as $bp)
                                                        <option value="{{ $bp->id }}" {{ (old('base_id', $product->base_id) == $bp->id) ? 'selected' : '' }}>
                                                            {{ $bp->name }} (SKU: {{ $bp->sku ?: 'N/A' }} | In-Stock: {{ $bp->stock ?? 0 }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-5 mb-2" id="base_multiplier_container" style="{{ $product->base_id ? '' : 'display: none;' }}">
                                            <label class="tx-12 font-weight-bold text-secondary">Quantity Multiplier (Pcs)</label>
                                            <input type="number" name="base_multiplier" id="base_multiplier" min="1" value="{{ old('base_multiplier', $product->base_multiplier ?? 1) }}" class="form-control form-control-sm" placeholder="e.g. 2 for 2-pack, 3 for 3-pack">
                                            <small class="text-muted">How many base units in 1 combo pack</small>
                                        </div>
                                        <div class="col-12" id="combo_notice" style="{{ $product->base_id ? '' : 'display: none;' }}">
                                            <div class="alert alert-warning py-2 px-3 tx-12 mb-0 border shadow-xs d-flex justify-content-between align-items-center flex-wrap" style="color: inherit;">
                                                <div class="py-1">
                                                    <i class="fa fa-info-circle mr-1 text-warning"></i> <strong class="text-dark font-weight-bold">Combo Pack Mode Active:</strong> <span>Stock is linked to base product. To manage inventory directly or make this a base product, remove the base product link.</span>
                                                </div>
                                                <div class="py-1">
                                                    <button type="button" class="btn btn-sm btn-danger font-weight-bold shadow-xs" id="btn_make_base_product">
                                                        <i class="fa fa-unlink"></i> Remove Base Product (Make Independent)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3" id="stock_management_row" style="{{ $product->base_id ? 'display: none;' : '' }}">
                                        <label class="col-sm-3 form-control-label font-weight-bold">Maintain Stock?</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <div class="custom-control custom-checkbox mt-2">
                                                <input type="checkbox" class="custom-control-input" id="is_stock_checkbox" name="is_stock" value="1" {{ old('is_stock', $product->is_stock) ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold text-dark" for="is_stock_checkbox">Enable stock & inventory tracking for this product</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">If enabled, this product will appear in the stock dashboard, allow purchase intakes, and track inventory on deliveries/returns.</small>
                                        </div>
                                    </div>

                                    <div class="row mt-3" id="stock_input_row" style="{{ ($product->base_id || !old('is_stock', $product->is_stock)) ? 'display: none;' : '' }}">
                                        <label class="col-sm-3 form-control-label font-weight-bold">Stock </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{ $product->stock }}" name="stock" id="product_stock_input"
                                                class="form-control" autocomplete="off" placeholder="Enter Stock">
                                        </div>
                                    </div>



                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group col-12 ">
                                        <h5 class="mb-1">Attributes</h5>
                                        <div class="form-row">
                                            @php
                                                $selectedAttributes = !empty($product->atr) ? (json_decode($product->atr, true) ?: []) : [];
                                                $selectedItems = !empty($product->atr_item) ? (json_decode($product->atr_item, true) ?: []) : [];
                                            @endphp
                                            @foreach (App\Models\ProductAttribute::all() as $attribute)
                                                <div class="form-group col-md-3 col-12">
                                                    <input type="checkbox" name="atr[]" class="attribute_id"
                                                        @if (in_array($attribute->id, $selectedAttributes)) checked @endif
                                                        value="{{ $attribute->id }}">

                                                    <label class="text-capitalize" for="">{{ $attribute->name }}</label>
                                                    <div>
                                                        @foreach (App\Models\Atr_item::where('atr_id', $attribute->id)->get() as $att_item)
                                                            <p class="mb-0">
                                                                <input type="checkbox"
                                                                    @if (in_array($att_item->id, $selectedItems)) checked @endif
                                                                    name="att_item[]" class="attribute_item"
                                                                    value="{{ $att_item->id }}">
                                                                <label class="text-capitalize"
                                                                    for="">{{ $att_item->name }}</label>
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                </div>
                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Featured thumbnail* </label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <img src="{{ asset('backend/img/products/' . $product->image) }}" width="50">
                                        <input type="file" @if ($product->image == null) required="required" @endif
                                            name="image" class="form-control-file">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Gallery Image</label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        @if ($product->gallery_images)
                                            @foreach (json_decode($product->gallery_images) as $area)
                                                <img src="{{ asset('backend/img/products/' . $area) }}" width="50">
                                            @endforeach
                                        @endif
                                        <input type="file" name="gallery_images[]" multiple class="form-control-file">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Status</label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <select name="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="1"@if ($product->status == 1) selected @endif>
                                                Published</option>
                                            <option value="0"@if ($product->status == 0) selected @endif>
                                                Unpublished</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Free Shipping</label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <input type="radio" name="shipping" value="1"
                                            {{ $product->shipping == '1' ? 'checked' : '' }}>
                                        <label for="html">YES</label><br>
                                        <input type="radio" name="shipping" value="0"
                                            {{ $product->shipping == '0' ? 'checked' : '' }}>
                                        <label for="css">NO</label><br>
                                        <input type="radio" name="shipping" value="2"
                                            {{ $product->shipping == '2' ? 'checked' : '' }}>
                                        <label for="css">NORMAL</label><br>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Shipping Cost (Inside Dhaka) </label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <input type="number" value="{{ $product->inside }}" name="inside"
                                            class="form-control" autocomplete="off" required="required">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Shipping Cost (Outside Dhaka) </label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <input type="number" value="{{ $product->outside }}" name="outside"
                                            class="form-control" autocomplete="off" required="required">
                                    </div>
                                </div>




                                <div class="row mt-3">
                                    <label class="col-sm-3 form-control-label">Product Assign</label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <select name="assigned_employees[]" class="form-control select2"
                                            multiple="multiple">
                                            @foreach (App\Models\User::where('role', 3)->get() as $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($product->assignedEmployees->pluck('id')->contains($user->id)) selected @endif>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Select one or more employees to assign to this
                                            product</small>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="form-group mt-4">
                        <label>Description </label>
                        <textarea name="description" class="form-control ckeditor " rows="4">{{ $product->description }}</textarea>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="submit" name="addproduct" value="Update product"
                                class="btn btn-teal btn-block mg-b-10">
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var baseSelect = $('#base_id_select');
            var multiplierContainer = $('#base_multiplier_container');
            var comboNotice = $('#combo_notice');
            var stockManagementRow = $('#stock_management_row');
            var stockInputRow = $('#stock_input_row');
            var isStockCheckbox = $('#is_stock_checkbox');
            var productStockInput = $('#product_stock_input');
            var removeBaseBtn = $('#remove_base_product_btn');

            if ($.fn.select2) {
                baseSelect.select2({
                    width: '100%'
                });
            }

            function toggleComboFields() {
                var selectedVal = baseSelect.val();
                var isCombo = selectedVal && selectedVal !== '' && selectedVal !== '0' && selectedVal !== 0;

                if (isCombo) {
                    multiplierContainer.show();
                    comboNotice.show();
                    stockManagementRow.hide();
                    stockInputRow.hide();
                    removeBaseBtn.show();
                } else {
                    multiplierContainer.hide();
                    comboNotice.hide();
                    stockManagementRow.show();
                    removeBaseBtn.hide();
                    if (isStockCheckbox.is(':checked')) {
                        stockInputRow.show();
                    } else {
                        stockInputRow.hide();
                    }
                }
            }

            // Button to uncouple combo and convert to independent base product
            $(document).on('click', '#remove_base_product_btn, #btn_make_base_product', function(e) {
                e.preventDefault();
                baseSelect.val('0').trigger('change');
                isStockCheckbox.prop('checked', true).trigger('change');
                if (!productStockInput.val() || productStockInput.val().trim() === '') {
                    productStockInput.val('0');
                }
                stockManagementRow.show();
                stockInputRow.show();
                productStockInput.focus();
            });

            isStockCheckbox.on('change', function() {
                if ($(this).is(':checked')) {
                    stockInputRow.slideDown(200);
                } else {
                    stockInputRow.slideUp(200);
                }
            });

            baseSelect.on('change', function() {
                var val = $(this).val();
                var isCombo = val && val !== '' && val !== '0' && val !== 0;
                if (!isCombo) {
                    if (!isStockCheckbox.is(':checked')) {
                        isStockCheckbox.prop('checked', true).trigger('change');
                        if (!productStockInput.val() || productStockInput.val().trim() === '') {
                            productStockInput.val('0');
                        }
                    }
                }
                toggleComboFields();
            });
            toggleComboFields();
        });
    </script>
@endsection
