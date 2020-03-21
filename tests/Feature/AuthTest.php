<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_successfully()
    {
        factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', ['email' => 'admin@admin.com', 'password' => 'password123']);

        $response->assertStatus(302);

        $response->assertRedirect('/home');
    }

    public function test_authenticated_user_can_access_products_table()
    {
        $user = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    // public function test_unauthenticated_user_cannot_access_products_table()
    // {
    //     // Go to homepage
    //     // Assert status 503
    // }
}
