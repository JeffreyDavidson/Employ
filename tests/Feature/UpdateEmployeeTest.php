<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEmployeeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Pass in valid parameters.
     *
     * @param  array  $overrides
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_replace([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'telephone' => '1234567890',
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_editing_an_employee_for_a_company()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.edit', [$employee->company, $employee]));

        $response->assertStatus(200);
        $response->assertViewIs('employees.edit');
    }

    /** @test */
    public function a_manager_can_view_the_form_for_editing_an_employee_in_the_company_they_are_assigned_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $employee->company]);

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.edit', [$employee->company, $employee]));

        $response->assertStatus(200);
    }

    /** @test */
    public function a_manager_cannot_view_the_form_for_editing_an_employee_they_are_not_assigned_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.employees.edit', [$employee->company, $employee]));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_an_employee()
    {
        $employee = factory(Employee::class)->create();

        $response = $this->get(route('companies.employees.edit', [$employee->company, $employee]));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_administrator_can_update_an_employee()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams());

        tap($employee->fresh(), function ($employee) {
            $this->assertEquals('John', $employee->first_name);
            $this->assertEquals('Smith', $employee->last_name);
            $this->assertEquals('john@example.com', $employee->email);
            $this->assertEquals('1234567890', $employee->telephone);
        });
    }

    /** @test */
    public function a_manager_can_update_an_employee_that_is_in_the_company_they_are_assigned_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $employee->company]);

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams());

        tap($employee->fresh(), function ($employee) {
            $this->assertEquals('John', $employee->first_name);
            $this->assertEquals('Smith', $employee->last_name);
            $this->assertEquals('john@example.com', $employee->email);
            $this->assertEquals('1234567890', $employee->telephone);
        });
    }

    /** @test */
    public function a_manager_cannot_update_an_employee_that_is_not_in_the_company_they_are_assigned_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->patch(route('companies.update', [$employee->company, $employee]), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_update_an_employee()
    {
        $employee = factory(Employee::class)->create();

        $response = $this->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function employee_first_name_is_required()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'first_name' => '',
                        ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function employee_first_name_must_be_a_string()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'first_name' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function employee_last_name_is_required()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
            ->from(route('companies.employees.edit', [$employee->company, $employee]))
            ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                'last_name' => '',
            ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function employee_last_name_must_be_a_string()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
            ->from(route('companies.employees.edit', [$employee->company, $employee]))
            ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                'last_name' => ['not-a-string'],
            ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function employee_email_is_not_required()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'email' => '',
                        ]));

        $response->assertSessionHasNoErrors('email');
    }

    /** @test */
    public function employee_email_must_be_a_string_when_provided()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'email' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function employee_email_must_be_a_valid_email_when_provided()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'email' => 'not-a-valid-email',
                        ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function employee_telephone_is_not_required()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'telephone' => '',
                        ]));

        $response->assertSessionHasNoErrors('telephone');
    }

    /** @test */
    public function employee_telephone_must_be_a_string_if_present()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.employees.edit', [$employee->company, $employee]))
                        ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                            'telephone' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('telephone');
    }

    /** @test */
    public function employee_telephone_must_be_a_ten_digits_long_if_present()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
            ->from(route('companies.employees.edit', [$employee->company, $employee]))
            ->patch(route('companies.employees.update', [$employee->company, $employee]), $this->validParams([
                'telephone' => '123456789',
            ]));

        $response->assertRedirect(route('companies.employees.edit', [$employee->company, $employee]));
        $response->assertSessionHasErrors('telephone');
    }
}
