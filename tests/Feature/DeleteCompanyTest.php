<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_a_company()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->delete(route('companies.destroy', $company));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('companies', ['name' => $company->name]);
    }

    /** @test */
    public function a_manager_cannot_delete_a_company()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($user)
                        ->delete(route('companies.destroy', $company));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_delete_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->delete(route('companies.destroy', $company));

        $response->assertRedirect('/login');
    }
}
