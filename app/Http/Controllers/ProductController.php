<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $url = $request->input('url');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');
        $stock = $request->input('stock');

        if ($id) {
            $product = Product::with('category', 'galleries')->find($id);

            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
            }
        }

        $product = Product::with('category', 'galleries');

        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }

        if ($description) {
            $product->where('description', 'like', '%' . $description . '%');
        }

        if ($tags) {
            $product->where('tags', 'like', '%' . $tags . '%');
        }

        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $product->where('price', '<=', $price_to);
        }

        if ($categories) {
            $product->where('categories_id', $categories);
        }

        if ($stock) {
            $product->where('stock', $stock);
        }

        if($url){
            $product->where('url', $url);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list produk berhasil diambil'
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'url' => ['required', 'string'],
            'categories_id' => ['required', 'integer', 'exists:product_categories,id'],
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Update product fails', 401);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'url' => $request->url,
            'categories_id' => $request->categories_id,
        ]);

        if ($product) {
            return ResponseFormatter::success(
                $product,
                'Data produk berhasil ditambahkan'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk gagal ditambahkan',
                404
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'price' => ['integer'],
            'stock' => ['integer'],
            'description' => ['string'],
            'url' => ['string'],
            'categories_id' => ['integer', 'exists:product_categories,id'],
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Update product fails', 401);
        }

        $product = Product::find($id);
        $product->update($request->all());

        if ($product) {
            return ResponseFormatter::success(
                $product,
                'Data produk berhasil diupdate'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk gagal diupdate',
                404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        $product = Product::find($id);
        $product->delete();

        if ($product) {
            return ResponseFormatter::success(
                $product,
                'Data produk berhasil dihapus'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data produk gagal dihapus',
                404
            );
        }
    }
}
