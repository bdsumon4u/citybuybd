<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Subcategory;
use File;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class Categorycontroller extends Controller
{
    public function index()
    {

        $categories = Category::get();

        return view('backend.pages.category.manage', compact('categories'));

    }

    public function create()
    {

        return view('backend.pages.category.create');
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->title = $request->title;
        $category->status = $request->status;
        //   $category->serial      = $request->serial;
        if ($request->image) {
            $image = $request->file('image');
            $img = random_int(0, mt_getrandmax()).'.'.$image->getClientOriginalExtension();
            $location = 'backend/img/category/'.$img;
            Image::make($image)->resize(400, 400)->save($location);
            $category->image = $img;

        }
        $category->save();
        $notification = [
            'message' => 'category Added successfully',
            'alert-type' => 'info',
        ];

        return to_route('category.manage')->with($notification);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (! is_null($category)) {
            return view('backend.pages.category.edit', compact('category'));
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->title = $request->title;
        $category->status = $request->status;
        //  $category->serial      = $request->serial;
        if ($request->image) {
            if (File::exists('backend/img/category/'.$category->image)) {
                File::delete('backend/img/category/'.$category->image);
            }
            $image = $request->file('image');
            $img = random_int(0, mt_getrandmax()).'.'.$image->getClientOriginalExtension();
            $location = 'backend/img/category/'.$img;
            Image::make($image)->resize(400, 400)->save($location);
            $category->image = $img;
        }
        $category->save();
        $notification = [
            'message' => 'category Update successfully',
            'alert-type' => 'success',
        ];

        return to_route('category.manage')->with($notification);

    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (! is_null($category)) {
            if (File::exists('Backend/img/category/'.$category->image)) {
                File::delete('Backend/img/category/'.$category->image);

            }
            $category->delete();
            $notification = [
                'message' => 'category deleted!',
                'alert-type' => 'error',
            ];

            return to_route('category.manage')->with($notification);
        }
    }

    // sub Category

    public function sub_index()
    {
        $categories = Subcategory::orderby('title', 'asc')->get();

        return view('backend.pages.subcategory.manage', compact('categories'));

    }

    public function sub_create()
    {
        $categories = Category::all();

        return view('backend.pages.subcategory.create', compact('categories'));
    }

    public function sub_store(Request $request)
    {
        $category = new Subcategory;
        $category->title = $request->title;
        $category->category_id = $request->category_id;
        $category->status = $request->status;
        $category->save();
        $notification = [
            'message' => 'category Added successfully',
            'alert-type' => 'info',
        ];

        return to_route('subcategory.manage')->with($notification);
    }

    public function sub_show($id)
    {
        //
    }

    public function sub_edit($id)
    {
        $subcategory = Subcategory::find($id);
        $categories = Category::all();
        if (! is_null($subcategory)) {
            return view('backend.pages.subcategory.edit', compact('subcategory', 'categories'));
        }
    }

    public function sub_update(Request $request, $id)
    {
        $category = Subcategory::find($id);
        $category->title = $request->title;
        $category->category_id = $request->category_id;
        $category->status = $request->status;

        $category->save();
        $notification = [
            'message' => 'category Update successfully',
            'alert-type' => 'success',
        ];

        return to_route('subcategory.manage')->with($notification);

    }

    public function sub_destroy($id)
    {
        $category = Subcategory::find($id);
        if (! is_null($category)) {

            $category->delete();
            $notification = [
                'message' => 'category deleted!',
                'alert-type' => 'error',
            ];

            return to_route('subcategory.manage')->with($notification);
        }
    }

    // Child Category

    public function child_index()
    {
        $categories = Childcategory::orderby('title', 'asc')->get();

        return view('backend.pages.childcategory.manage', compact('categories'));

    }

    public function child_create()
    {
        $categories = Category::all();

        return view('backend.pages.childcategory.create', compact('categories'));
    }

    public function child_store(Request $request)
    {
        $category = new Childcategory;
        $category->title = $request->title;
        $category->category_id = $request->category_id;
        $category->subcategory_id = $request->subcategory_id;
        $category->status = $request->status;
        $category->save();
        $notification = [
            'message' => 'category Added successfully',
            'alert-type' => 'info',
        ];

        return to_route('childcategory.manage')->with($notification);
    }

    public function child_show($id)
    {
        //
    }

    public function child_edit($id)
    {
        $childcategory = Childcategory::find($id);
        $categories = Category::all();
        $subcategory = Subcategory::find($childcategory->subcategory_id);

        if (! is_null($childcategory)) {
            return view('backend.pages.childcategory.edit', compact('childcategory', 'categories', 'subcategory'));
        }
    }

    public function child_update(Request $request, $id)
    {

        $category = Childcategory::find($id);

        $category->title = $request->title;
        $category->category_id = $request->category_id;
        $category->subcategory_id = $request->subcategory_id;
        $category->status = $request->status;

        $category->save();
        $notification = [
            'message' => 'category Update successfully',
            'alert-type' => 'success',
        ];

        return to_route('childcategory.manage')->with($notification);

    }

    public function child_destroy($id)
    {
        $category = Childcategory::find($id);
        if (! is_null($category)) {

            $category->delete();
            $notification = [
                'message' => 'category deleted!',
                'alert-type' => 'error',
            ];

            return to_route('childcategory.manage')->with($notification);
        }
    }

    public function indexBrand()
    {
        $brands = Brand::orderby('title', 'asc')->get();

        return view('backend.pages.brand.manage', compact('brands'));

    }

    public function createBrand()
    {

        return view('backend.pages.brand.create');
    }

    public function storeBrand(Request $request)
    {
        $brand = new Brand;
        $brand->title = $request->title;
        $brand->status = $request->status;
        $brand->save();
        $notification = [
            'message' => 'Brand Added successfully',
            'alert-type' => 'info',
        ];

        return to_route('brand.manage')->with($notification);
    }

    public function showBrand($id)
    {
        //
    }

    public function editBrand($id)
    {
        $brand = Brand::find($id);
        if (! is_null($brand)) {
            return view('backend.pages.brand.edit', compact('brand'));
        }
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::find($id);
        $brand->title = $request->title;
        $brand->status = $request->status;
        $brand->save();
        $notification = [
            'message' => 'brand Update successfully',
            'alert-type' => 'success',
        ];

        return to_route('brand.manage')->with($notification);

    }

    public function destroyBrand($id)
    {
        $brand = Brand::find($id);
        if (! is_null($brand)) {

            $brand->delete();
            $notification = [
                'message' => 'brand deleted!',
                'alert-type' => 'error',
            ];

            return to_route('brand.manage')->with($notification);
        }
    }
}
