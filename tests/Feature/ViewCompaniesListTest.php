<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCompaniesListTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function an_administrator_can_view_list_of_companies()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.index'));

        $response->assertStatus(200);
        $response->assertViewIs('companies.index');
        $response->assertViewHas('companies');
    }

    /** @test */
    public function a_manager_cannot_view_list_of_companies()
    {
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
            ->get(route('companies.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_list_of_companies()
    {
        $response = $this->get(route('companies.index'));

        $response->assertRedirect('/login');
    }
}
