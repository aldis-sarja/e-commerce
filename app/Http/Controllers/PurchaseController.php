<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;


class PurchaseController extends Controller
{
    private PurchaseService $service;

    public function __construct(PurchaseService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        $request->validate([
            'products' => 'required'
        ]);

        $cart = $this->service->create($request);

        $orderCode = $cart->getPurchases()[0]->order_code;

        if (!($request->cookie('order_code'))) {
            $response = response($cart)->cookie('order_code', $orderCode, 5);
        } else {
            $response = response($cart);
        }

        return view('welcome', [
            'products' => (new ProductService)->getAll(),
            'cart' => $response
        ]);

    }

    public function get(Request $request)
    {
        $cart = $this->service->get($request);

        if (!$cart) {
            return view('welcome', ['products' => (new ProductService)->getAll()]);
        }

        return view('welcome', [
            'products' => (new ProductService)->getAll(),
            'cart' => response($cart)
        ]);
    }

    public function remove(Request $request)
    {
        $cart = $this->service->remove($request);

        if (!$cart) {
            return view('welcome', ['products' => (new ProductService)->getAll()]);
        }

        return view('cart', ['cart' => response($cart)]);
    }


    public function buy(Request $request)
    {
        $orderCode = $this->service->buy($request);

        if(!$orderCode) {
            return view('welcome', ['products' => (new ProductService)->getAll()]);
        }

        return view('purchase-confirmation', ['code' => $orderCode]);
    }
}
