<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Tests\Testcase;

class PurchaseTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;

    public function test_it_should_be_able_to_make_purchase_model()
    {
        $cart = new Purchase([
            'order_code' => 'a000',
            'product_id' => 1
        ]);

        $this->assertEquals('a000', $cart->order_code);
        $this->assertEquals(1, $cart->product_id);
    }

    public function test_it_should_be_able_to_add_product_to_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductController)->create($request);

        $product = Product::all()->first();

        $request = new Request([
            'products' => [$product->id]
        ]);
        $cart = (new PurchaseController)->create($request)->original[0];

//        $cart->original[0]->product->price;

        foreach ($cart as $purchase) {
            $this->assertEquals('a000', $cart->order_code);
            $this->assertEquals(51000, $cart->product->price);
            $this->assertEquals(21, $cart->product->VAT);
        }
    }

    public function test_it_should_be_able_to_add_more_products_to_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductController)->create($request);
        (new ProductController)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseController)->create($request)->original[0];


        $this->assertEquals(2, $cart->count());

        foreach ($cart as $purchase) {
            $this->assertEquals('a000', $cart->order_code);
            $this->assertEquals(51000, $cart->product->price);
            $this->assertEquals(21, $cart->product->VAT);
        }
    }

    public function test_it_should_be_able_to_get_products_from_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductController)->create($request);
        (new ProductController)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseController)->create($request)->original[0];

        $request = new Request([
            'order_code' => $cart->order_code
        ]);
        $cart = (new PurchaseController)->get($request)->original[0];

        $this->assertEquals(2, $cart->count());
    }

    public function test_it_should_be_able_to_remove_products_from_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductController)->create($request);
        (new ProductController)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseController)->create($request)->original[0];

        $this->assertEquals(2, $cart->count());

        $ids = [$cart->first()->id];

        $request = new Request([
            'order_code' => $cart->order_code,
            'products' => $ids,
        ]);

        $cart = (new PurchaseController)->remove($request)->original[0];

        $this->assertEquals(1, $cart->count());
    }
}
