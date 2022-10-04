<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductService
{
    public function create(Request $request)
    {
        $product = new Product([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'VAT' => $request->VAT,
        ]);

        $product->save();
    }

    public function getAll()
    {

        $products = Product::where('reserved', null)->get();
        if ($products->count() > 0) {
            $product = $products->first();

            $collection = collect([
                $product->name => collect()
            ]);
            foreach ($products as $product) {
                $item = $collection->get($product->name);
                if (!$item) {
                    $collection->put($product->name, collect());

                    $item = $collection->get($product->name);
                }
                    $item->push(new ProductResource($product));

            }
            return $collection;
        }
        return null;
    }

    public function getByName(string $name)
    {
        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $name]
        ])->get();


        if ($products->count() > 0) {
            $collection = collect();
            foreach ($products as $product) {
                $collection->push(new ProductResource($product));
            }

            return $collection;
        }

        return response()->json([
            'errors' => [
                'message' => 'Invalid product name'
            ]],
            404);
    }

    public function createAmount(Request $request)
    {
        for ($c = 0; $c < $request->get('amount'); $c++) {
            $product = new Product([
                'name' => $request->name,
                'price' => $request->price * 100,
                'description' => $request->description,
                'VAT' => $request->VAT,
            ]);
            $product->save();
        }
    }

    public function remove(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }

    public function reserve($id)
    {
        $product = Product::findOrFail($id);
        $product->reserved = Carbon::now();
        $product->save();
    }

    public function reserveUnset($id)
    {
        $product = Product::findOrFail($id);
        $product->reserved = null;
        $product->save();
    }

    public function changePrice(int $id, int $price)
    {
        $product = Product::findOrFail($id);
        $product->price = $price;
        $product->save();
    }

    public function changePriceByName(Request $request)
    {
        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $request->name]
        ])->get();

        foreach ($products as $product) {
            $product->price = $request->price;
            $product->save();
        }
    }

    public function changeVAT(int $id, int $VAT)
    {
        $product = Product::findOrFail($id);
        $product->VAT = $VAT;
        $product->save();
    }

    public function changeVATByName(Request $request)
    {
        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $request->name]
        ])->get();

        foreach ($products as $product) {
            $product->VAT = $request->VAT;
            $product->save();
        }
    }

    public function changeName(Request $request)
    {
        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $request->name]
        ])->get();

        foreach ($products as $product) {
            $product->update([
                'name' => $request->new_name
            ]);
        }
    }

    public function changeAmount(Request $request)
    {
        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $request->name]
        ])->get();

        $currentAmount = $products->count();

        if ($currentAmount === $request->amount) {
            return;
        }

        if ($request->amount > $currentAmount) {

            $this->createAmount(
                new Request([
                    'name' => $products->first()->name,
                    'price' => $products->first()->price,
                    'description' => $products->first()->description,
                    'VAT' => $products->first()->VAT,
                    'amount' =>  $request->amount - $currentAmount
                ]));
        } else {
            $times = $currentAmount - $request->amount;
            $count = 1;
            foreach ($products as $product) {
                $product->delete();
                $count++;
                if ($count > $times) {
                    break;
                }
            }
        }
    }
}
