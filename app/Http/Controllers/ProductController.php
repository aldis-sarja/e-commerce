<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'price' => 'required|gt:0',
                'description' => 'required',
                'VAT' => 'required',
            ]
        );

        $this->service->create($request);
    }

    public function getAll()
    {

        return view('welcome', ['data' => $this->service->getAll()]);

    }

    public function getByName(string $name)
    {
        return view('product', ['data' => $this->service->getByName($name)]);
    }

    public function createAmount(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'price' => 'required|gt:0',
                'description' => 'required',
                'VAT' => 'required',
                'amount' => 'required|gt:0',
            ]
        );

        $this->service->createAmount($request);
    }

    public function remove(int $id)
    {
        $this->service->remove($id);
    }

    public function reserve($id)
    {
        $this->service->remove($id);
    }

    public function reserveUnset($id)
    {
        $this->service->reserveUnset($id);
    }

    public function changePrice(int $id, int $price)
    {
        $this->reserve($id, $price);
    }

    public function changePriceByName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|gt:0',
        ]);

        $this->service->changePriceByName($request);
    }

    public function changeVAT(int $id, int $VAT)
    {
        $this->service->changeVAT($id, $VAT);
    }

    public function changeVATByName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'VAT' => 'required',
        ]);

        $this->service->changeVATByName($request);
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'new_name' => 'required',
        ]);

        $this->service->changeName($request);
    }

    public function changeAmount(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'amount' => 'required',
            ]
        );

        $this->service->changeAmount($request);
    }
}
