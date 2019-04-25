<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_company_page()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.show', $company));

        $response->assertStatus(200);
        $response->assertViewIs('companies.show');
        $response->assertViewHas('company');
        $response->assertSee($company->name);
    }

    /** @test */
    public function a_manager_can_view_a_company_page_they_are_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($user)
                        ->get(route('companies.show', $company));

        $response->assertStatus(200);
    }

    /** @test */
    public function a_manager_cannot_view_a_company_page_they_are_not_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.show', $company));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_a_company_page()
    {
        $company = factory(Company::class)->create();

        $response = $this->get(route('companies.show', $company));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_companys_managers_can_be_seen_on_the_company_page()
    {
        $company = factory(Company::class)->create();
        $userA = factory(User::class)->states('administrator')->create();
        $userB = factory(User::class)->states('manager')->create(['company_id' => $company]);
        $userC = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($userA)
                        ->get(route('companies.show', $company));

        $response->assertSee(e($userB->name));
        $response->assertSee(e($userC->name));
    }
}
