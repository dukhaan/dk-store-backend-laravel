<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;


class ProductCategoryController extends Controller
{

    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if($id){
            $category = ProductCategory::with(['products'])->find($id);

            if($category){
                return ResponseFormatter::success(
                    $category,
                    'Data kategori berhasil diambil'
                );
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404
                );
            }
        }

        $category = ProductCategory::query();

        if($id){
            $category->where('id', $id);
        }

        if($name){
            $category->where('name', 'like', '%' . $name . '%');
        }

        if($show_product){
            $category->with('products');
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data list kategori berhasil diambil'
        );

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        //Validate the request...
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $productCategory = ProductCategory::create([
            'name' => $request->name,
        ]);

        if ($productCategory) {
            return ResponseFormatter::success(
                $productCategory,
                'Data kategori berhasil ditambahkan'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data kategori gagal ditambahkan',
                400
            );
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories|max:255',
        ]);

        if($validator->fails()){
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Update product fails', 401);
        }

        $productCategory = ProductCategory::find($id);
        $productCategory->update([
            'name' => $request->name,
        ]);

        if ($productCategory) {
            return ResponseFormatter::success(
                $productCategory,
                'Data kategori berhasil diupdate'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data kategori gagal diupdate',
                400
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory, $id)
    {
        $productCategory = ProductCategory::find($id);
        $productCategory->delete();

        if ($productCategory) {
            return ResponseFormatter::success(
                $productCategory,
                'Data kategori berhasil dihapus'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data kategori gagal dihapus',
                400
            );
        }
    }
}
