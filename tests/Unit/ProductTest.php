<?php

namespace Tests\Unit;

use App\Services\ProductService;
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

        (new ProductService)->create($request);

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
            $request = new Request([
                'name' => 'TV05',
                'price' => 38000,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $request = new Request([
            'name' => 'TV06',
            'price' => 38000,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $product = (new ProductService)->getAll()->first()->first();

        $this->assertNotNull($product);

        $this->assertEquals('TV05', $product->name);
        $this->assertEquals(38000, $product->price);
        $this->assertEquals('Hello World!', $product->description);
        $this->assertEquals(21, $product->VAT);


    }

    public function test_it_should_be_able_to_get_one_type_of_product()
    {
        (new ProductService)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $products = (new ProductService)->getByName("Hammer");
        $this->assertNotNull($products);

        $this->assertEquals('Hammer', $products->first()->name);
    }

    public function test_it_should_be_able_to_get_one_type_of_two_products()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
        ]);
        (new ProductService)->create($request);
        (new ProductService)->create($request);

        $products = (new ProductService)->getByName("Hammer");
        $this->assertNotNull($products);

        $this->assertEquals('Hammer', $products->first()->name);
        $this->assertEquals(2, $products->count());
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
        (new ProductService)->createAmount($request);

        $products = (new ProductService)->getByName("Hammer");
        $this->assertNotNull($products);

        $this->assertEquals('Hammer', $products->first()->name);
        $this->assertEquals(5, $products->count());
    }

    public function test_it_should_be_able_to_remove_product()
    {
        (new ProductService)->create(
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
        (new ProductService)->remove($product->id);

        $product = Product::firstWhere($product->id);

        $this->assertNull($product);
    }

    public function test_it_should_be_able_to_reserve_product()
    {
        (new ProductService)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::all()->first();

        // Reserve
        (new ProductService)->reserve($product->id);

        $product = Product::find($product->id);

        $this->assertNotNull($product->reserved);
    }

    public function test_it_should_be_able_to_unset_reserved_flag_for_product()
    {
        (new ProductService)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::all()->first();
        $id = $product->id;

        (new ProductService)->reserve($id);
        $product = Product::find($id);
        $this->assertNotNull($product->reserved);

        (new ProductService)->reserveUnset($id);
        $product = Product::find($id);
        $this->assertNull($product->reserved);
    }

    public function test_it_should_be_able_to_change_price_for_product()
    {
        (new ProductService)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::firstWhere([
            ['name', '=', 'Hammer']
        ]);

        (new ProductService)->changePrice($product->id, 1300);

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
        (new ProductService)->createAmount($request);

        (new ProductService)->changePriceByName(new Request([
            'name' => 'Hammer',
            'price' => 14.00,
        ]));

        $products = Product::where([
            ['name', '=', 'Hammer']
        ])->get();

        foreach ($products as $product) {
            $this->assertEquals(1400, $product->price);
        }
    }

    public function test_it_should_be_able_to_change_VAT_for_product()
    {
        (new ProductService)->create(
            new Request([
                'name' => 'Hammer',
                'price' => 1200,
                'description' => 'Hello World!',
                'VAT' => 21,
            ]));

        $product = Product::firstWhere([
            ['name', '=', 'Hammer']
        ]);

        (new ProductService)->changeVAT($product->id, 13);

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
        (new ProductService)->createAmount($request);

        (new ProductService)->changeVATByName(new Request([
            'name' => 'Hammer',
            'VAT' => 13,
        ]));

        $products = Product::where([
            ['name', '=', 'Hammer']
        ])->get();

        foreach ($products as $product) {
            $this->assertEquals(13, $product->VAT);
        }
    }

    public function test_it_should_be_able_to_change_name_for_product()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 3,
        ]);
        (new ProductService)->createAmount($request);

        (new ProductService)->changeName(new Request([
            'name' => 'Hammer',
            'new_name' => 'Axe',
        ]));

        $products = Product::firstWhere([
            ['name', '=', 'Axe']
        ]);

        $this->assertEquals('Axe', $products->name);
    }

    public function test_it_should_be_able_to_increase_amount_of_product()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 1,
        ]);
        (new ProductService)->createAmount($request);

        (new ProductService)->changeAmount(new Request([
            'name' => 'Hammer',
            'amount' => 3,
        ]));

        $products = Product::where([
            ['name', '=', 'Hammer']
        ])->get();

        $this->assertEquals(3, $products->count());
    }

    public function test_it_should_be_able_to_decrease_amount_of_product()
    {
        $request = new Request([
            'name' => 'Hammer',
            'price' => 1200,
            'description' => 'Hello World!',
            'VAT' => 21,
            'amount' => 3,
        ]);
        (new ProductService)->createAmount($request);

        (new ProductService)->changeAmount(new Request([
            'name' => 'Hammer',
            'amount' => 2,
        ]));

        $products = Product::where([
            ['name', '=', 'Hammer']
        ])->get();

        $this->assertEquals(2, $products->count());
    }
}
