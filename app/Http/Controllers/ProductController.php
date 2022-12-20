<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    private ProductService $productService;
    private PurchaseService $purchaseService;

    public function __construct(ProductService $productService, PurchaseService $purchaseService)
    {
        $this->productService = $productService;
        $this->purchaseService = $purchaseService;
    }

    public function getAll(Request $request)
    {
        $routeName = Route::current()->getName();

        if ($routeName !== 'products') {
            $cart = $this->purchaseService->get($request);
        } else {
            $cart = null;
        }

        return view($routeName, [
            'products' => $this->productService->getAll(),
            'cart' => $cart
        ]);

    }

    public function getByName(string $name)
    {
        return view('product', ['product' => $this->productService->getByName($name)]);
    }

    public function create(Request $request)
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

        return redirect('/products');
    }

    public function remove(int $id)
    {
        $this->productService->remove($id);
    }

    public function reserve(int $id)
    {
        $this->productService->remove($id);
    }

    public function reserveUnset(int $id)
    {
        $this->productService->reserveUnset($id);
    }


    public function changePrice(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|gt:0',
        ]);

        $this->productService->changePriceByName($request);

        $routeName = Route::current()->getName();
        return redirect('/' . $routeName . '/' . $request->get('name'));
    }

    public function changeVat(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'VAT' => 'required',
        ]);

        $this->productService->changeVatByName($request);

        $routeName = Route::current()->getName();
        return redirect('/' . $routeName . '/' . $request->get('name'));
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'new_name' => 'required',
        ]);

        $this->productService->changeName($request);

        $routeName = Route::current()->getName();
        return redirect('/' . $routeName . '/' . $request->get('new_name'));
    }

    public function changeDescription(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $this->productService->changeDescription($request);

        $routeName = Route::current()->getName();
        return redirect('/' . $routeName . '/' . $request->get('name'));
    }

    public function changeAmount(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'amount' => 'required',
            ]
        );

        $this->productService->changeAmount($request);

        $routeName = Route::current()->getName();
        if ($request->get('amount')) {

            return redirect('/' . $routeName . '/' . $request->get('name'));
        } else {
            return redirect('/' . $routeName);
        }
    }
}
