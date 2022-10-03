<?php

namespace App\Services;

use App\Helper\Base62;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function create(Request $request)
    {
        $orderCode = $this->getOrMakeOrderCode($request);

        foreach ($request->get('products') as $id) {
            Product::findOrFail($id);
            (new ProductService)->reserve($id);
            $purchase = new Purchase([
                'order_code' => $orderCode,
                'product_id' => $id
            ]);
            $purchase->save();
        }

        return new Cart(Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get(), $orderCode);
    }

    public function get(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {
            return null;
        }

        return new Cart(Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get(), $orderCode);
    }

    public function remove(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {
            return null;
        }

        foreach ($request->get('products') as $id) {
            $product = Purchase::firstWhere([
                ['order_code', '=', $orderCode],
                ['product_id', '=', $id],
            ]);
            if ($product) {
                $product->delete();
                (new ProductService)->reserveUnset($id);
            }
        }

        return new Cart(Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get(), $orderCode);

    }

    public function buy(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {
            return null;
        }

        $purchases = Purchase::where([
            ['order_code', '=', $orderCode]
        ])->with(['product'])->get();

        foreach ($purchases as $purchase) {
            $purchase->update([
                'confirmed' => Carbon::now(),
                'sent' => Carbon::now()
            ]);

            $product = Product::find($purchase->product_id);
            $product->delete();
        }
        return $orderCode;
    }

    private function createNewCode()
    {
        $id = intval(DB::table('counter')->select('count')->get()->first()->count);

        DB::table('counter')->update([
            'count' => $id + 1
        ]);

        return Base62::encode($id);
    }

    private function getOrMakeOrderCode(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {

            $orderCode = $this->createNewCode();
        }

        return $orderCode;
    }

    private function checkForOrderCode(Request $request)
    {
        $orderCode = $request->get('order_code');

        if (!$orderCode) {

            $tryCookie = $request->cookie('order_code');

            if ($tryCookie) {

                $orderCode = $tryCookie;
            }
        }
        return $orderCode;
    }
}
