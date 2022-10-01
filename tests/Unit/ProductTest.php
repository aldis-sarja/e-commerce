<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Http\Request;
//use PHPUnit\Framework\TestCase;
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

        $products = (new ProductController)->getAll();

        $this->assertNotNull($products);
    }

    public function test_it_should_be_able_to_get_one_type_of_product()
    {
        $product = (new ProductController)->getByName("Hammer");
        $this->assertNotNull($product);

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
    }
}
