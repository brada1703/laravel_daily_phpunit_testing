<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123')
        ]);
    }

    public function test_homepage_contains_empty_products_table()
    {
        $this->withoutExceptionHandling();

        // $user = $this->create_user();

        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);

        $response->assertSee('No products found');
    }

    public function test_homepage_contains_non_empty_products_table()
    {
        $this->withoutExceptionHandling();

        // $user = $this->create_user();

        $product = Product::create([
            'name' => 'Product1',
            'price' => 99.99
        ]);

        $response = $this->actingAs($this->user)->get('/');

        $response->assertStatus(200);

        $response->assertDontSee('No products found.');

        $response->assertSee($product->name);

        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
    }

    public function test_paginated_products_table_doesnt_show_11th_record()
    {
        // $user = $this->create_user();

        $response = $this->actingAs($this->user)->get('/');

        $products = factory(Product::class, 11)->create();

        $response->assertDontSee($products->last()->name);
    }

    // private function create_user()
    // {
    //     return factory(User::class)->create([
    //         'email' => 'admin@admin.com',
    //         'password' => bcrypt('password123')
    //     ]);
    // }
}
