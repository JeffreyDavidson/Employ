<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function validParams($overrides = [])
    {
        return array_replace([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'telephone' => '9876543210',
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_an_employee_for_a_given_company()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.create', $company));

        $response->assertStatus(200);
        $response->assertViewIs('employees.create');
    }

    /** @test */
    public function a_manager_can_view_the_form_for_creating_an_employee_for_the_company_they_are_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.create', $company));

        $response->assertStatus(200);
        $response->assertViewIs('employees.create');
    }

    /** @test */
    public function a_manager_cannot_view_the_form_for_creating_an_employee_for_a_company_they_are_not_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.create', $company));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_an_employee_for_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->get(route('companies.employees.create', $company));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_administrator_can_add_an_employee_to_a_company()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->post(route('companies.employees.store', $company), $this->validParams());

        tap($company->employees()->first(), function ($employee) {
            $this->assertEquals('John', $employee->first_name);
            $this->assertEquals('Smith', $employee->last_name);
            $this->assertEquals('john@example.com', $employee->email);
            $this->assertEquals('9876543210', $employee->telephone);
        });
    }

    /** @test */
    public function a_manager_can_add_an_employee_to_a_company_they_are_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($user)
                        ->post(route('companies.employees.store', $company), $this->validParams());

        tap($company->employees()->first(), function ($employee) {
            $this->assertEquals('John', $employee->first_name);
            $this->assertEquals('Smith', $employee->last_name);
            $this->assertEquals('john@example.com', $employee->email);
            $this->assertEquals('9876543210', $employee->telephone);
        });
    }

    /** @test */
    public function a_manager_cannot_add_an_employee_to_a_company_they_are_not_attached_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
            ->post(route('companies.employees.store', $company), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_add_an_employee_to_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->post(route('companies.employees.store', $company), $this->validParams());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_employee_first_name_is_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'first_name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('first_name');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_first_name_must_be_a_string()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'first_name' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('first_name');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_last_name_is_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'last_name' => '',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('last_name');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_last_name_must_be_a_string()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'last_name' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('last_name');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_email_is_not_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'email' => '',
                        ]));

        $response->assertSessionHasNoErrors('email');
    }

    /** @test */
    public function an_employee_email_must_be_a_string_if_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'email' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_email_must_be_a_valid_email_type_if_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'email' => 'adfds.com',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, $company->employees->count());
    }

    /** @test */
    public function an_employee_telephone_is_not_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'telephone' => '',
                        ]));

        $response->assertSessionHasNoErrors('telephone');
    }

    /** @test */
    public function an_employee_telephone_must_be_a_string_if_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.create', $company))
                        ->post(route('companies.employees.store', $company), $this->validParams([
                            'telephone' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.employees.create', $company));
        $response->assertSessionHasErrors('telephone');
        $this->assertEquals(0, $company->employees->count());
    }
}
