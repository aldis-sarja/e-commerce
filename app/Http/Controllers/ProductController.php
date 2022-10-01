<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
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
}
