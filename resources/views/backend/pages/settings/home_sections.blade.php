@extends('backend.layout.template')
@section('body-content')

<div class="br-pagebody">
    <div class="row">
        <div class="col-lg-12">
            <div class="card bd-0 pd-20 shadow-sm" style="border-radius: 8px;">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="text-dark font-weight-bold mb-1">
                            <i class="fa-solid fa-layer-group text-primary mr-2"></i> Homepage Sections Management
                        </h4>
                        <p class="text-muted mb-0" style="font-size: 13px;">
                            Configure, reorder, and customize dynamic sections for both Araz and Frontend storefront themes.
                        </p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-toggle="modal" data-target="#addSectionModal">
                        <i class="fa fa-plus mr-1"></i> Add New Section
                    </button>
                </div>

                @if (session('message'))
                    <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <div class="table-responsive">
                    <form id="reorderForm" action="{{ route('settings.homeSections.reorder') }}" method="POST">
                        @csrf
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 80px;" class="text-center">Order</th>
                                    <th>Section Title & Subtitle</th>
                                    <th>Section Type</th>
                                    <th>Data Source / Filter</th>
                                    <th style="width: 100px;" class="text-center">Product Limit</th>
                                    <th style="width: 110px;" class="text-center">Display Style</th>
                                    <th style="width: 90px;" class="text-center">Status</th>
                                    <th style="width: 130px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sections as $section)
                                    <tr>
                                        <td class="text-center">
                                            <input type="number" name="orders[{{ $section->id }}]" value="{{ $section->order_index }}" class="form-control form-control-sm text-center font-weight-bold" style="width: 60px; margin: 0 auto;">
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark" style="font-size: 14px;">{{ $section->title }}</span>
                                            @if($section->subtitle)
                                                <div class="text-muted small">{{ $section->subtitle }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'banner_slider' => ['label' => 'Hero Banner Slider', 'color' => 'info'],
                                                    'trust_badges' => ['label' => 'Trust Badges Strip', 'color' => 'secondary'],
                                                    'categories_grid' => ['label' => 'Categories Grid', 'color' => 'purple'],
                                                    'hot_deals' => ['label' => 'Hot Deals / Offers', 'color' => 'danger'],
                                                    'best_selling' => ['label' => 'Best Selling', 'color' => 'success'],
                                                    'latest_products' => ['label' => 'Latest Products', 'color' => 'primary'],
                                                    'category_products' => ['label' => 'Category Showcase', 'color' => 'warning'],
                                                    'custom_products' => ['label' => 'Custom Handpicked', 'color' => 'dark'],
                                                    'all_products' => ['label' => 'All Products Grid', 'color' => 'info'],
                                                ];
                                                $badgeInfo = $badges[$section->section_type] ?? ['label' => ucfirst(str_replace('_', ' ', $section->section_type)), 'color' => 'secondary'];
                                            @endphp
                                            <span class="badge badge-{{ $badgeInfo['color'] }} px-2 py-1" style="font-size: 12px;">
                                                {{ $badgeInfo['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($section->section_type === 'category_products')
                                                <span class="text-dark font-weight-semibold">Category:</span> {{ $section->category->title ?? 'All Top Categories' }}
                                            @elseif($section->section_type === 'custom_products')
                                                <span class="badge badge-light border">{{ count($section->product_ids ?? []) }} Products Selected</span>
                                            @elseif(in_array($section->section_type, ['hot_deals', 'best_selling', 'latest_products', 'all_products']))
                                                <span class="text-muted small">Sort: <strong>{{ str_replace('_', ' ', ucfirst($section->product_sort)) }}</strong></span>
                                            @else
                                                <span class="text-muted small">System Dynamic</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!in_array($section->section_type, ['banner_slider', 'trust_badges', 'categories_grid']))
                                                <span class="font-weight-bold">{{ $section->product_limit }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light border text-capitalize">{{ $section->display_style ?: 'Grid' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('settings.homeSections.toggle', $section->id) }}" class="badge badge-{{ $section->is_active ? 'success' : 'danger' }} px-2 py-1" title="Click to toggle status" style="cursor: pointer; text-decoration: none;">
                                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-info edit-section-btn" 
                                                data-id="{{ $section->id }}"
                                                data-title="{{ $section->title }}"
                                                data-subtitle="{{ $section->subtitle }}"
                                                data-type="{{ $section->section_type }}"
                                                data-category_id="{{ $section->category_id }}"
                                                data-product_ids="{{ json_encode($section->product_ids ?? []) }}"
                                                data-product_sort="{{ $section->product_sort }}"
                                                data-product_limit="{{ $section->product_limit }}"
                                                data-display_style="{{ $section->display_style }}"
                                                data-order_index="{{ $section->order_index }}"
                                                data-is_active="{{ $section->is_active ? '1' : '0' }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn" 
                                                data-id="{{ $section->id }}"
                                                data-title="{{ $section->title }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No homepage sections configured. Click "Add New Section" to create one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if($sections->count() > 0)
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-success btn-sm px-4">
                                    <i class="fa fa-save mr-1"></i> Save Section Ordering
                                </button>
                            </div>
                        @endif
                    </form>

                    <form id="globalDeleteForm" action="" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD SECTION MODAL -->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('settings.homeSections.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-dark">Add New Homepage Section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold">Section Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. হট ডিল ও অফার / Hot Deals" required>
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold">Section Type <span class="text-danger">*</span></label>
                            <select name="section_type" class="form-control section-type-select" required>
                                <option value="hot_deals">Hot Deals (Offer Price / Discounted)</option>
                                <option value="best_selling">Best Selling (Most Ordered)</option>
                                <option value="latest_products">Latest Products (New Arrivals)</option>
                                <option value="category_products">Category Showcase (Selected Category)</option>
                                <option value="custom_products">Custom Products (Handpicked List)</option>
                                <option value="all_products">All Products Grid (With Pagination)</option>
                                <option value="categories_grid">Categories Grid Strip</option>
                                <option value="trust_badges">Trust / Service Badges Strip</option>
                                <option value="banner_slider">Hero Banner Slider</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Subtitle (Optional)</label>
                            <input type="text" name="subtitle" class="form-control" placeholder="e.g. সীমিত সময়ের জন্য বিশেষ মূল্যছাড়ের সুযোগ নিন">
                        </div>

                        <!-- Category Selector (Conditional) -->
                        <div class="col-md-6 form-group category-picker-wrap" style="display: none;">
                            <label class="font-weight-bold">Select Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- All Top Categories Loop --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Selector (Conditional) -->
                        <div class="col-md-12 form-group custom-products-wrap" style="display: none;">
                            <label class="font-weight-bold">Select Products</label>
                            <select name="product_ids[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (৳{{ $p->offer_price ?? $p->regular_price }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap">
                            <label class="font-weight-bold">Product Sort Order</label>
                            <select name="product_sort" class="form-control">
                                <option value="latest">Latest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="price_low_high">Price: Low to High</option>
                                <option value="price_high_low">Price: High to Low</option>
                                <option value="discount_high_low">Highest Discount</option>
                                <option value="random">Random Order</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap">
                            <label class="font-weight-bold">Product Display Limit</label>
                            <input type="number" name="product_limit" class="form-control" value="12" min="1" max="100">
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap">
                            <label class="font-weight-bold">Display Style</label>
                            <select name="display_style" class="form-control">
                                <option value="grid">Standard Grid</option>
                                <option value="highlight_box">Highlight Gradient Box</option>
                                <option value="slider">Carousel Slider</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Order Position</label>
                            <input type="number" name="order_index" class="form-control" placeholder="Sequence number (e.g. 1, 2, 3...)">
                        </div>

                        <div class="col-md-6 form-group d-flex align-items-center mt-md-4 pt-md-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActiveCheck" value="1" checked>
                                <label class="custom-control-label font-weight-bold" for="isActiveCheck">Active / Visible on Storefront</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save mr-1"></i> Create Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT SECTION MODAL -->
<div class="modal fade" id="editSectionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editSectionForm" action="" method="POST">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold text-dark">Edit Homepage Section</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold">Section Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-md-5 form-group">
                            <label class="font-weight-bold">Section Type <span class="text-danger">*</span></label>
                            <select name="section_type" id="edit_section_type" class="form-control section-type-select" required>
                                <option value="hot_deals">Hot Deals (Offer Price / Discounted)</option>
                                <option value="best_selling">Best Selling (Most Ordered)</option>
                                <option value="latest_products">Latest Products (New Arrivals)</option>
                                <option value="category_products">Category Showcase (Selected Category)</option>
                                <option value="custom_products">Custom Products (Handpicked List)</option>
                                <option value="all_products">All Products Grid (With Pagination)</option>
                                <option value="categories_grid">Categories Grid Strip</option>
                                <option value="trust_badges">Trust / Service Badges Strip</option>
                                <option value="banner_slider">Hero Banner Slider</option>
                            </select>
                        </div>

                        <div class="col-md-12 form-group">
                            <label class="font-weight-bold">Subtitle (Optional)</label>
                            <input type="text" name="subtitle" id="edit_subtitle" class="form-control">
                        </div>

                        <!-- Category Selector (Conditional) -->
                        <div class="col-md-6 form-group category-picker-wrap" id="edit_category_wrap" style="display: none;">
                            <label class="font-weight-bold">Select Category</label>
                            <select name="category_id" id="edit_category_id" class="form-control">
                                <option value="">-- All Top Categories Loop --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Selector (Conditional) -->
                        <div class="col-md-12 form-group custom-products-wrap" id="edit_custom_products_wrap" style="display: none;">
                            <label class="font-weight-bold">Select Products</label>
                            <select name="product_ids[]" id="edit_product_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (৳{{ $p->offer_price ?? $p->regular_price }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap" id="edit_sort_wrap">
                            <label class="font-weight-bold">Product Sort Order</label>
                            <select name="product_sort" id="edit_product_sort" class="form-control">
                                <option value="latest">Latest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="price_low_high">Price: Low to High</option>
                                <option value="price_high_low">Price: High to Low</option>
                                <option value="discount_high_low">Highest Discount</option>
                                <option value="random">Random Order</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap" id="edit_limit_wrap">
                            <label class="font-weight-bold">Product Display Limit</label>
                            <input type="number" name="product_limit" id="edit_product_limit" class="form-control" min="1" max="100">
                        </div>

                        <div class="col-md-4 form-group product-settings-wrap" id="edit_style_wrap">
                            <label class="font-weight-bold">Display Style</label>
                            <select name="display_style" id="edit_display_style" class="form-control">
                                <option value="grid">Standard Grid</option>
                                <option value="highlight_box">Highlight Gradient Box</option>
                                <option value="slider">Carousel Slider</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Order Position</label>
                            <input type="number" name="order_index" id="edit_order_index" class="form-control">
                        </div>

                        <div class="col-md-6 form-group d-flex align-items-center mt-md-4 pt-md-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="edit_is_active" value="1">
                                <label class="custom-control-label font-weight-bold" for="edit_is_active">Active / Visible on Storefront</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save mr-1"></i> Update Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleTypeChange(selectElement, parentModal) {
        const type = selectElement.value;
        const categoryWrap = parentModal.querySelector('.category-picker-wrap');
        const customProductsWrap = parentModal.querySelector('.custom-products-wrap');
        const productSettingsWraps = parentModal.querySelectorAll('.product-settings-wrap');

        if (categoryWrap) {
            categoryWrap.style.display = (type === 'category_products') ? 'block' : 'none';
        }
        if (customProductsWrap) {
            customProductsWrap.style.display = (type === 'custom_products') ? 'block' : 'none';
        }

        const isSystemStatic = ['banner_slider', 'trust_badges', 'categories_grid'].includes(type);
        productSettingsWraps.forEach(function(wrap) {
            wrap.style.display = isSystemStatic ? 'none' : 'block';
        });
    }

    document.querySelectorAll('.section-type-select').forEach(function(select) {
        select.addEventListener('change', function() {
            handleTypeChange(this, this.closest('.modal-content'));
        });
    });

    document.querySelectorAll('.edit-section-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const subtitle = this.getAttribute('data-subtitle') || '';
            const type = this.getAttribute('data-type');
            const categoryId = this.getAttribute('data-category_id') || '';
            const productIds = JSON.parse(this.getAttribute('data-product_ids') || '[]');
            const productSort = this.getAttribute('data-product_sort') || 'latest';
            const productLimit = this.getAttribute('data-product_limit') || 12;
            const displayStyle = this.getAttribute('data-display_style') || 'grid';
            const orderIndex = this.getAttribute('data-order_index') || 0;
            const isActive = this.getAttribute('data-is_active') === '1';

            const form = document.getElementById('editSectionForm');
            form.action = "{{ url('admin/settings/home-sections/update') }}/" + id;

            document.getElementById('edit_title').value = title;
            document.getElementById('edit_subtitle').value = subtitle;
            document.getElementById('edit_section_type').value = type;
            document.getElementById('edit_category_id').value = categoryId;
            document.getElementById('edit_product_sort').value = productSort;
            document.getElementById('edit_product_limit').value = productLimit;
            document.getElementById('edit_display_style').value = displayStyle;
            document.getElementById('edit_order_index').value = orderIndex;
            document.getElementById('edit_is_active').checked = isActive;

            handleTypeChange(document.getElementById('edit_section_type'), form);

            $('#editSectionModal').modal('show');
        });
    });

    document.querySelectorAll('.delete-section-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title') || 'this';
            if (confirm('Are you sure you want to delete "' + title + '" section?')) {
                const form = document.getElementById('globalDeleteForm');
                form.action = "{{ url('admin/settings/home-sections/delete') }}/" + id;
                form.submit();
            }
        });
    });
});
</script>
@endsection
