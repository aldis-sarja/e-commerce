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
        $products = $request->get('products');
        if (!is_array($products)) {
            $products = [$products];
        }
        foreach ($products as $id) {
            $this->add($id, $orderCode);
        }

        return new Cart([
            'purchases' => Purchase::where([
                ['order_code', '=', $orderCode]
            ])->with(['product'])->get(),
            'order_code' => $orderCode
        ]);
    }

    public function addToPurchase(Request $request): bool
    {
        $orderCode = $this->checkForOrderCode($request);
        if (!$orderCode) {
            return false;
        }

        $product = Product::where([
            ['name', '=', $request->get('name')],
            ['reserved', '=', null]
        ])->first();
        if (!$product) {
            return false;
        }

        return $this->add($product->id, $orderCode);
    }

    private function add(int $id, string $orderCode)
    {
        Product::findOrFail($id);
        (new ProductService)->reserve($id);
        $purchase = new Purchase([
            'order_code' => $orderCode,
            'product_id' => $id
        ]);
        return $purchase->save();
    }

    public function get(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {
            return null;
        }

        $cart = new Cart([
            'purchases' => Purchase::where([
                ['order_code', '=', $orderCode]])
                ->with(['product'])->get(),
            'order_code' => $orderCode
        ]);

        if ($cart->purchases->count() < 1) {
            return null;
        }
        return $cart;

    }

    public function remove(Request $request)
    {
        $orderCode = $this->checkForOrderCode($request);

        if (!$orderCode) {
            return null;
        }

        $ids = $request->get('products');
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        foreach ($ids as $id) {
            $product = Purchase::firstWhere([
                ['order_code', '=', $orderCode],
                ['product_id', '=', $id],
            ]);
            if ($product) {
                $product->delete();
                (new ProductService)->reserveUnset($id);
            }
        }
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

            $purchase->confirmed = Carbon::now();
            $purchase->sent = Carbon::now();
            $purchase->save();

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
