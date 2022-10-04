<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Ramsey\Collection\Collection;

class ProductController extends Controller
{
    private ProductService $productService;
    private PurchaseService $purchaseService;

    public function __construct(ProductService $productService, PurchaseService $purchaseService)
    {
        $this->productService = $productService;
        $this->purchaseService = $purchaseService;
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

        $this->productService->create($request);
    }

    public function getAll(Request $request)
    {
        $cart = $this->purchaseService->get($request);


        return view(Route::current()->getName(), [
            'products' => $this->productService->getAll(),
            'cart' => $cart
        ]);

    }

    public function getByName(string $name)
    {
        return view('product', ['products' => $this->productService->getByName($name)]);
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

        $this->productService->createAmount($request);
    }

    public function remove(int $id)
    {
        $this->productService->remove($id);
    }

    public function reserve($id)
    {
        $this->productService->remove($id);
    }

    public function reserveUnset($id)
    {
        $this->productService->reserveUnset($id);
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

        $this->productService->changePriceByName($request);
    }

    public function changeVAT(int $id, int $VAT)
    {
        $this->productService->changeVAT($id, $VAT);
    }

    public function changeVATByName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'VAT' => 'required',
        ]);

        $this->productService->changeVATByName($request);
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'new_name' => 'required',
        ]);

        $this->productService->changeName($request);
    }

    public function changeAmount(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'amount' => 'required',
            ]
        );

        $this->productService->changeAmount($request);
    }
}
