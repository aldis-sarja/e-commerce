<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
use Tests\Testcase;

class ProductTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;

    public function test_it_should_be_able_to_make_product_model()
    {
        $product = new Product([
            'name' => 'TV01',
            'price' => 38000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        $this->assertEquals('TV01', $product->name);
        $this->assertEquals(38000, $product->price);
        $this->assertEquals('Hello World!', $product->description);
        $this->assertEquals(21, $product->VAT);
    }

    public function test_it_should_be_able_to_add_product()
    {
        $request = new Request([
            'name' => 'TV03',
            'price' => 38000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);

        (new ProductController)->create($request);

        $product = Product::firstWhere([
            ['name', '=', 'TV03'],
            ['price', '=', 38000],
            ['description', '=', 'Hello World!'],
            ['VAT', '=', 21],
        ]);

        $this->assertNotNull($product);
        $this->assertEquals('TV03', $product->name);
        $this->assertEquals(38000, $product->price);
        $this->assertEquals('Hello World!', $product->description);
        $this->assertEquals(21, $product->VAT);
    }

    public function test_it_should_be_able_to_get_product_list()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'TV05',
                'price' => 38000,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = (new ProductController)->getAll()->first();

        $this->assertNotNull($product);

        $this->assertEquals('TV05', $product->name);
        $this->assertEquals(38000, $product->price);
        $this->assertEquals('Hello World!', $product->description);
        $this->assertEquals(21, $product->VAT);
        $this->assertEquals(1, $product->amount);

    }

    public function test_it_should_be_able_to_get_one_type_of_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = (new ProductController)->getByName("Hammer");
        $this->assertNotNull($product);

        $this->assertEquals('Hammer', $product->name);
    }

    public function test_it_should_be_able_to_get_one_type_of_two_products()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductController)->create($request);
        (new ProductController)->create($request);

        $product = (new ProductController)->getByName("Hammer");
        $this->assertNotNull($product);

        $this->assertEquals('Hammer', $product->name);
        $this->assertEquals(2, $product->amount);
    }

    public function test_it_should_be_able_to_add_amount_of_products()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 5,
        ]);
        (new ProductController)->createAmount($request);

        $product = (new ProductController)->getByName("Hammer");
        $this->assertNotNull($product);

        $this->assertEquals('Hammer', $product->name);
        $this->assertEquals(5, $product->amount);
    }

    public function test_it_should_be_able_to_remove_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::firstWhere([
            ['name', '=', 'Hammer'],
            ['price', '=', 1200],
            ['description', '=', 'Hello World!'],
            ['VAT', '=', 21],
        ]);

        $this->assertEquals('Hammer', $product->name);

        // Remove
        (new ProductController)->remove($product->id);

        $product = Product::firstWhere($product->id);

        $this->assertNull($product);
    }

    public function test_it_should_be_able_to_reserve_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = (new ProductController)->getByName("Hammer");

        // Reserve
        (new ProductController)->reserve($product->id);

        $product = Product::find($product->id);

        $this->assertNotNull($product->reserved);
    }

    public function test_it_should_be_able_to_unset_reserved_flag_for_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = (new ProductController)->getByName("Hammer");
        $id = $product->id;

        (new ProductController)->reserve($id);
        $product = Product::find($id);
        $this->assertNotNull($product->reserved);

        (new ProductController)->reserveUnset($id);
        $product = Product::find($id);
        $this->assertNull($product->reserved);
    }

    public function test_it_should_be_able_to_change_price_for_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::firstWhere([
            ['name', '=', 'Hammer']
        ]);

        (new ProductController)->changePrice($product->id, 1300);

        $product = Product::find($product->id);

        $this->assertEquals(1300, $product->price);
    }

    public function test_it_should_be_able_to_change_price_for_products_by_name()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 3,
        ]);
        (new ProductController)->createAmount($request);

        (new ProductController)->changePriceByName(new Request([
            'name' => 'Hammer',
            'price' => 1400,
        ]));

        $products = (new ProductController)->getAll();
        foreach ($products as $product) {
            $this->assertEquals(1400, $product->price);
        }
    }

    public function test_it_should_be_able_to_change_VAT_for_product()
    {
        (new ProductController)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::firstWhere([
            ['name', '=', 'Hammer']
        ]);

        (new ProductController)->changeVAT($product->id, 13);

        $product = Product::find($product->id);
        $this->assertEquals(13, $product->VAT);
    }

    public function test_it_should_be_able_to_change_VAT_for_products_by_name()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 3,
        ]);
        (new ProductController)->createAmount($request);

        (new ProductController)->changeVATByName(new Request([
            'name' => 'Hammer',
            'VAT' => 13,
        ]));

        $products = (new ProductController)->getAll();
        foreach ($products as $product) {
            $this->assertEquals(13, $product->VAT);
        }
    }
}
