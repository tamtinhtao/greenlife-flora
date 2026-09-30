<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH SẢN PHẨM
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->get();

        return view(
            'products.index',
            compact('products')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM THÊM SẢN PHẨM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $categories = Category::all();

        return view(
            'products.create',
            compact('categories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU SẢN PHẨM
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        $data['slug'] = Str::slug(
            $request->name
        );

        if ($request->hasFile('image')) {

            $imageName =
                time() . '.' .
                $request->image->extension();

            $request->image->move(
                public_path('uploads/products'),
                $imageName
            );

            $data['image'] =
                'uploads/products/' . $imageName;
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Thêm cây cảnh thành công!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM SỬA
    |--------------------------------------------------------------------------
    */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view(
            'products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Product $product
    ) {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $data = $request->all();

        $data['slug'] = Str::slug(
            $request->name
        );

        if ($request->hasFile('image')) {

            $imageName =
                time() . '.' .
                $request->image->extension();

            $request->image->move(
                public_path('uploads/products'),
                $imageName
            );

            $data['image'] =
                'uploads/products/' . $imageName;
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Cập nhật cây cảnh thành công!'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA
    |--------------------------------------------------------------------------
    */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Xóa sản phẩm thành công!'
            );
    }
}