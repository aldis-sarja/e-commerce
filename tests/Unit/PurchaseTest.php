<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\ProductService;
use App\Services\PurchaseService;
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
            'price' => 510,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);

        $product = Product::all()->first();

        $request = new Request([
            'products' => [$product->id]
        ]);

        $cart = (new PurchaseService)->create($request);

        foreach ($cart as $purchase) {
            $this->assertEquals('a000', $cart->order_code);
            $this->assertEquals(510, $cart->purchases->first()->product->price);
            $this->assertEquals(21, $cart->purchases->first()->product->VAT);
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
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);


        $this->assertEquals(2, $cart->purchases->count());

        foreach ($cart as $purchase) {
            $this->assertEquals('a000', $cart->order_code);
            $this->assertEquals(51000, $cart->purchases->first()->product->price);
            $this->assertEquals(21, $cart->purchases->first()->product->VAT);
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
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $request = new Request([
            'order_code' => $cart->order_code
        ]);
        $cart = (new PurchaseService)->get($request);

        $this->assertEquals(2, $cart->purchases->count());
    }

    public function test_it_should_be_able_to_remove_products_from_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $this->assertEquals(2, $cart->purchases->count());

        $ids = [$cart->purchases->first()->product->id];

        $request = new Request([
            'order_code' => $cart->order_code,
            'products' => $ids,
        ]);

        (new PurchaseService)->remove($request);

        $request = new Request([
            'order_code' => $cart->order_code,
        ]);
        $cart = (new PurchaseService)->get($request);

        $this->assertEquals(1, $cart->purchases->count());
    }

    public function test_it_should_be_able_to_get_subtotal_of_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $this->assertEquals(102000, $cart->getSubTotal());
    }

    public function test_it_should_be_able_to_get_Vat_amount_of_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $this->assertEquals(21420, $cart->getVatSum());
    }

    public function test_it_should_be_able_to_get_total_of_cart()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $this->assertEquals(123420, $cart->getTotal());
    }


    public function test_it_should_be_able_to_buy_products()
    {
        $request = new Request([
            'name' => 'TV',
            'price' => 51000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = Product::all();

        $ids = [];
        foreach ($products as $product) {
            $ids[] = $product->id;
        }

        $request = new Request([
            'products' => $ids
        ]);

        $cart = (new PurchaseService)->create($request);

        $request = new Request([
            'order_code' => $cart->order_code
        ]);

        $orderCode = (new PurchaseService)->buy($request);

        $this->assertEquals('a000', $orderCode);

        foreach ($ids as $id) {
            $product = Product::find($id);
            $this->assertNull($product);
        }
    }
}
