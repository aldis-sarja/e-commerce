<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


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
        if ($cart) {
            if (!$request->cookie('order_code')) {
                Cookie::queue('order_code', $cart->order_code, 1440);
            }
        }

        return redirect('/');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $this->service->addToPurchase($request);

        return redirect('/cart');
    }

    public function get(Request $request)
    {
        $cart = $this->service->get($request);

        return view('cart', [
            'products' => (new ProductService)->getAll(),
            'cart' => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $this->service->remove($request);

        return redirect('/cart');

//        if (!$cart) {
//            return view('welcome', ['products' => (new ProductService)->getAll()]);
//        }

//        return view('cart', ['cart' => response($cart)]);
    }


    public function buy(Request $request)
    {
        $orderCode = $this->service->buy($request);

        if(!$orderCode) {

            return redirect('/');
        }
        Cookie::queue(Cookie::forget('order_code'));
//        Cookie::expire('order_code');

        return view('purchase-confirmation', ['code' => $orderCode]);
    }
}
