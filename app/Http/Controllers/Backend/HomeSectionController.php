<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::with('category')->orderBy('order_index', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('title', 'asc')->get();
        $products = Product::where('status', 1)->orderBy('name', 'asc')->select('id', 'name', 'regular_price', 'offer_price', 'image')->get();

        return view('backend.pages.settings.home_sections', compact('sections', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_type' => 'required|string|max:50',
            'product_limit' => 'nullable|integer|min:1|max:100',
            'order_index' => 'nullable|integer',
        ]);

        $maxOrder = HomeSection::max('order_index') ?? 0;

        HomeSection::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'section_type' => $request->section_type,
            'category_id' => $request->section_type === 'category_products' ? $request->category_id : null,
            'product_ids' => $request->section_type === 'custom_products' ? ($request->product_ids ?? []) : null,
            'product_sort' => $request->product_sort ?? 'latest',
            'product_limit' => $request->product_limit ?: 12,
            'display_style' => $request->display_style ?? 'grid',
            'order_index' => $request->filled('order_index') ? (int) $request->order_index : ($maxOrder + 1),
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()->back()->with([
            'message' => 'Homepage section added successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_type' => 'required|string|max:50',
            'product_limit' => 'nullable|integer|min:1|max:100',
            'order_index' => 'nullable|integer',
        ]);

        $section = HomeSection::findOrFail($id);

        $section->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'section_type' => $request->section_type,
            'category_id' => $request->section_type === 'category_products' ? $request->category_id : null,
            'product_ids' => $request->section_type === 'custom_products' ? ($request->product_ids ?? []) : null,
            'product_sort' => $request->product_sort ?? 'latest',
            'product_limit' => $request->product_limit ?: 12,
            'display_style' => $request->display_style ?? 'grid',
            'order_index' => $request->filled('order_index') ? (int) $request->order_index : $section->order_index,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        return redirect()->back()->with([
            'message' => 'Homepage section updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
        ]);

        foreach ($request->orders as $id => $orderIndex) {
            HomeSection::where('id', $id)->update(['order_index' => (int) $orderIndex]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sections reordered successfully.',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Sections reordered successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function toggleStatus($id)
    {
        $section = HomeSection::findOrFail($id);
        $section->is_active = ! $section->is_active;
        $section->save();

        return redirect()->back()->with([
            'message' => 'Section status updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $section = HomeSection::findOrFail($id);
        $section->delete();

        return redirect()->back()->with([
            'message' => 'Homepage section deleted successfully.',
            'alert-type' => 'success',
        ]);
    }
}
