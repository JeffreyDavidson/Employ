<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_login_page_can_be_viewed()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function an_administrator_can_login_with_valid_creditions()
    {
        $user = factory(User::class)->states('administrator')->create([
            'email' => 'admin@site.com',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@site.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }

    /** @test */
    public function a_manager_can_login_with_valid_creditions()
    {
        $user = factory(User::class)->states('manager')->create([
            'email' => 'manager@site.com',
            'password' => 'password',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'manager@site.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }
}
