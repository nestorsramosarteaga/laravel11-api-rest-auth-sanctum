<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Enums\HttpCodesEnum;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface){
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ProductResource::collection($data), '', HttpCodesEnum::OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = [
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try {
            $product = $this->productRepositoryInterface->store($data);
            DB::commit();

            return ApiResponseClass::sendResponse(new ProductResource($product),'Product created successful',HttpCodesEnum::CREATED);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = $this->productRepositoryInterface->getById($id);
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::throw($e->getMessage(), 'Register not found', HttpCodesEnum::NOT_FOUND);
        }  catch (\Exception $e) {
            return ApiResponseClass::rollback($e->getMessage());
        }
        return ApiResponseClass::sendResponse(new ProductResource($product),'',HttpCodesEnum::OK);
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
        $updateData = [
            'name' => $request->name,
            'details' => $request->details
        ];

        DB::beginTransaction();
        try {

            $product = $this->productRepositoryInterface->update($updateData, $id);

            DB::commit();

            return ApiResponseClass::sendResponse(new ProductResource($product),'Product Update Successful', HttpCodesEnum::OK);

        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productRepositoryInterface->delete($id);
        return ApiResponseClass::sendResponse([], 'Product Deleted Successfully', HttpCodesEnum::ACCEPTED);
    }
}
