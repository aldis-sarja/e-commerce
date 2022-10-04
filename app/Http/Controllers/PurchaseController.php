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

        $this->service->create($request);

        return redirect('/');
    }

    public function get(Request $request)
    {
        $cart = $this->service->get($request);

//        if (!$cart) {
//            return view('welcome', ['products' => (new ProductService)->getAll()]);
//        }
//        dd($cart->purchases->first());
//        dd(new CartResource($cart));

//        dd("BĻAĢ!", $cart);
//        $wtf = [
//            'order_code' => $cart->order_code,
//            'purchases' => $cart->purchases,
//            'price' => $cart->getTotal(),
//            'sub_total' => $cart->getSubTotal(),
//            'VAT_sum' => $cart->getVatSum()
//        ];
//        $wtf = new CartResource($cart);
//        dd($wtf->resource);
        return view('cart', [
            'products' => (new ProductService)->getAll(),
//            'cart' => $wtf
            'cart' => $cart
//            'cart' => response()->json($wtf)
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

        Cookie::expire('order_code');

        return view('purchase-confirmation', ['code' => $orderCode]);
    }
}
