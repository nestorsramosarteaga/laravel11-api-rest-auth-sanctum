<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Enums\HttpCodesEnum;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BrandResource;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Interfaces\CRUDRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends Controller
{
    private CRUDRepositoryInterface $brandRepositoryInterface;

    public function __construct(CRUDRepositoryInterface $brandRepositoryInterface){
        $this->brandRepositoryInterface = $brandRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->brandRepositoryInterface->index();

        return ApiResponseClass::
            sendResponse(BrandResource::collection($data),'', HttpCodesEnum::OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $data = [
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try {
            $brand = $this->brandRepositoryInterface->store($data);
            DB::commit();

            return ApiResponseClass::
                sendResponse(new BrandResource($brand),'Register created successful',HttpCodesEnum::CREATED);
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
            $brand = $this->brandRepositoryInterface->getById($id);
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::throw($e->getMessage(), 'Register not found', HttpCodesEnum::NOT_FOUND);
        }  catch (\Exception $e) {
            return ApiResponseClass::rollback($e->getMessage());
        }
        return ApiResponseClass::
            sendResponse(new BrandResource($brand),'',HttpCodesEnum::OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
