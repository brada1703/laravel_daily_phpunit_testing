<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_contains_empty_products_table()
    {
        $this->withoutExceptionHandling();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $product = Product::create([
            'name' => 'Product1',
            'price' => 99.99
        ]);

        $this->withoutExceptionHandling();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('No products found.');

        $response->assertSee($product->name);

        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        $products = factory(Product::class, 11)->create();

        info($products);

        $response = $this->get('/');

        $response->assertDontSee($products->last()->name);
    }
}
