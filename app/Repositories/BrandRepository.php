<?php

namespace App\Repositories;

use App\Interfaces\CRUDRepositoryInterface;
use App\Models\Brand;

class BrandRepository implements CRUDRepositoryInterface
{
    public function index()
    {
        return Brand::all();
    }

    public function getById($id)
    {
        return Brand::findOrFail($id);
    }

    public function store(array $data)
    {
        return Brand::create($data);
    }

    public function update(array $data, $id)
    {
        return Brand::whereId($id)->update($data);
    }

    public function delete($id)
    {
        Brand::destroy($id);
    }


}
