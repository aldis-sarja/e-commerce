<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'price' => 'required|gt:0',
                'description' => 'required',
                'VAT' => 'required',
            ]
        );
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

        $counts = [];
        foreach ($products as $product) {
            if (isset($counts[$product->name])) {

                $counts[$product->name]->amount++;
            } else {
                $product->amount = 1;
                $counts[$product->name] = $product;
            }
        }

        $collection = collect([]);
        foreach ($counts as $product) {
            $collection->push($product);
        }

        return ProductResource::collection($collection);
    }

    public function getByName(string $name)
    {
        $product = Product::where([
            ['reserved', '=', null],
            ['name', '=', $name]
        ])
            ->get();

        if ($product->count() > 0) {
            $p = $product->first();
            $p->amount =  $product->count();

            return $p;
        }

        return response()->json([
            'errors' => [
                'message' => 'Invalid product name'
            ]],
            404);
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
        for ($c = 0; $c < $request->get('amount'); $c++) {
            $product = new Product([
                'name' => $request->name,
                'price' => $request->price,
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
        $request->validate([
            'name' => 'required',
            'price' => 'required|gt:0',
        ]);

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
        $request->validate([
            'name' => 'required',
            'VAT' => 'required',
        ]);

        $products = Product::where([
            ['reserved', '=', null],
            ['name', '=', $request->name]
        ])->get();

        foreach ($products as $product) {
            $product->VAT = $request->VAT;
            $product->save();
        }
    }
}
