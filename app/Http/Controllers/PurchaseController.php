<?php

namespace App\Http\Controllers;

use App\Helper\Base62;
use App\Http\Resources\PurchaseResource;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'products' => 'required'
        ]);
        $orderCode = $request->get('order_code');
        if (!$orderCode) {

            $tryCookie = $request->cookie('order_code');
            if ($tryCookie) {
                $orderCode = $tryCookie;

            } else {

                $orderCode = $this->createNewCode();
            }
        }

        foreach ($request->get('products') as $id) {
            $product = Product::findOrFail($id);
            (new ProductController)->reserve($id);
            $purchase = new Purchase([
                'order_code' => $orderCode,
                'product_id' => $id
            ]);
            $purchase->save();
        }

        $cart = Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get();

        if ($request->cookie('order_code'))
        {
            return response(PurchaseResource::collection($cart));
        } else {
            return response(PurchaseResource::collection($cart))->cookie(
                'order_code', $orderCode, 5
            );
        }
    }

    public function get(Request $request)
    {
        $orderCode = $request->get('order_code');
        if (!$orderCode) {

            $tryCookie = $request->cookie('order_code');
            if (!$tryCookie) {
                $orderCode = $tryCookie;

            } else {

                return null;
            }
        }

        $cart = Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get();

        return response(PurchaseResource::collection($cart));
    }

    public function remove(Request $request)
    {
        $orderCode = $request->get('order_code');
        if (!$orderCode) {

            $tryCookie = $request->cookie('order_code');
            if ($tryCookie) {
                $orderCode = $tryCookie;

            } else {
                return null;
            }
        }


        foreach ($request->get('products') as $id) {
            $product = Purchase::firstWhere([
                ['order_code', '=', $orderCode],
                ['product_id', '=', $id],
            ]);
            if ($product) {
                $product->delete();
                (new ProductController)->reserveUnset($id);
            }
        }

        $cart = Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get();

        return response(PurchaseResource::collection($cart));
    }

    private function createNewCode()
    {
        $id = intval(DB::table('counter')->select('count')->get()->first()->count);

        DB::table('counter')->update([
            'count' => $id + 1
        ]);

        return Base62::encode($id);
    }
}
