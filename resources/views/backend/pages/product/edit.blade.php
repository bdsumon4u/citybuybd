@extends('backend.layout.template')
@section('body-content')

    <style>
        .cke_notifications_area {
            display: none;
        }
    </style>

    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bd-0 pd-10 overflow-hidden">
                        <form action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data"
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
                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Stock </label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            <input type="number" value="{{ $product->stock }}" name="stock"
                                                class="form-control" autocomplete="off" placeholder="Enter Stock">
                                        </div>
                                    </div>


                                    <!-- Bulk / Quantity-Based Pricing Card -->
                                    <div class="card mt-4 border">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-layer-group"></i> Bulk / Quantity-Based Pricing (Tier Packs)</h6>
                                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" id="add-bulk-tier-btn">
                                                <i class="fa fa-plus"></i> Add Tier
                                            </button>
                                        </div>
                                        <div class="card-body p-3">
                                            <p class="text-muted tx-12 mb-2">Configure multiple quantity packs (e.g. <strong>1 Pcs = 390৳</strong>, <strong>2 Pcs = 690৳</strong>, <strong>3 Pcs = 900৳</strong>) to display interactive quantity pack selectors on the product detail page.</p>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm mb-0" id="bulk-pricing-table">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th style="width: 22%;">Package Title<br>(e.g. 2 Pcs)</th>
                                                            <th style="width: 15%;">Quantity</th>
                                                            <th style="width: 20%;">Regular<br>Price (৳)</th>
                                                            <th style="width: 20%;">Offer<br>Price (৳)</th>
                                                            <th style="width: 18%; text-align: center;">Free<br>Delivery</th>
                                                            <th style="width: 5%; text-align: center;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="bulk-pricing-tbody">
                                                        <!-- Dynamic Rows populated by JS -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group col-12 ">
                                        <h5 class="mb-1">Attributes</h5>
                                        <div class="form-row">
                                            @foreach (App\Models\ProductAttribute::all() as $attribute)
                                                <div class="form-group col-md-3 col-12">
                                                    {{-- Decode the product's attribute array --}}
                                                    @php
                                                        $selectedAttributes = json_decode($product->atr, true);
                                                        $selectedItems = json_decode($product->atr_item, true);
                                                    @endphp

                                                    {{-- Check if the attribute is selected --}}
                                                    <input type="checkbox" name="atr[]" class="attribute_id"
                                                        @if (is_array($selectedAttributes) && in_array($attribute->id, $selectedAttributes)) checked @endif
                                                        value="{{ $attribute->id }}">
                                                    <label class="text-capitalize"
                                                        for="">{{ $attribute->name }}</label>

                                                    <div>
                                                        {{-- Loop through attribute items --}}
                                                        @foreach (App\Models\Atr_item::where('atr_id', $attribute->id)->get() as $att_item)
                                                            <p class="mb-0">
                                                                {{-- Check if the attribute item is selected --}}
                                                                <input type="checkbox"
                                                                    @if (is_array($selectedItems) && in_array($att_item->id, $selectedItems)) checked @endif
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
                                            <img src="{{ asset('backend/img/products/' . $product->image) }}"
                                                width="50">
                                            <input type="file"
                                                @if ($product->image == null) required="required" @endif name="image"
                                                class="form-control-file">
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
                                            <input type="file" name="gallery_images[]" multiple
                                                class="form-control-file">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <label class="col-sm-3 form-control-label">Video</label>
                                        <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                            @if (!is_null($product->video))
                                                <video width="350" controls>
                                                    <source
                                                        src="{{ asset('backend/img/products/video/' . $product->video) }}"
                                                        type="video/mp4">
                                                </video>
                                            @endif
                                            <input type="file" name="video" class="form-control-file"
                                                value={{ $product->video }}>
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
                                            <small class="form-text text-muted">Select one or more employees to assign to
                                                this product</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label>Description </label>
                                <textarea name="description" class="form-control summernote  ckeditor" rows="4">{!! $product->description !!}</textarea>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tbody = document.getElementById('bulk-pricing-tbody');
            var addBtn = document.getElementById('add-bulk-tier-btn');
            var existingTiers = @json($product->bulk_prices ?? []);
            var rowIndex = 0;

            function addTierRow(title = '', qty = '', regPrice = '', offPrice = '', freeShipping = false) {
                var tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <input type="text" name="bulk_prices[${rowIndex}][title]" class="form-control form-control-sm" placeholder="e.g. 1 Pcs, 2 Pcs" value="${title || ''}">
                    </td>
                    <td>
                        <input type="number" name="bulk_prices[${rowIndex}][quantity]" class="form-control form-control-sm" min="1" placeholder="Quantity" value="${qty || 1}">
                    </td>
                    <td>
                        <input type="number" step="any" name="bulk_prices[${rowIndex}][regular_price]" class="form-control form-control-sm" placeholder="Regular price" value="${regPrice ?? ''}">
                    </td>
                    <td>
                        <input type="number" step="any" name="bulk_prices[${rowIndex}][offer_price]" class="form-control form-control-sm" placeholder="Offer price" value="${offPrice ?? ''}">
                    </td>
                    <td class="text-center align-middle">
                        <div class="form-check d-flex align-items-center justify-content-center mb-0">
                            <input type="checkbox" name="bulk_prices[${rowIndex}][free_shipping]" value="1" class="form-check-input" id="bulk_free_ship_${rowIndex}" ${freeShipping ? 'checked' : ''} style="cursor: pointer; width: 16px; height: 16px;">
                            <label class="form-check-label tx-12 font-weight-semibold text-success ms-1 mb-0" for="bulk_free_ship_${rowIndex}" style="cursor: pointer;"></label>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-tier-btn" title="Remove Tier">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
                rowIndex++;
            }

            if (Array.isArray(existingTiers) && existingTiers.length > 0) {
                existingTiers.forEach(function(tier) {
                    var isFree = (tier.free_shipping == 1 || tier.free_shipping === true || tier.free_shipping === '1');
                    addTierRow(tier.title, tier.quantity, tier.regular_price, tier.offer_price, isFree);
                });
            }

            addBtn.addEventListener('click', function() {
                var defaultQty = tbody.children.length + 1;
                addTierRow(defaultQty + ' Pcs', defaultQty, '', '', false);
            });

            tbody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-tier-btn')) {
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>
@endsection
