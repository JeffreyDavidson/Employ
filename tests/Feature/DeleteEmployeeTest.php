<?php

namespace Tests\Feature;

use App\User;
use App\Employee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteEmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_an_employee_from_a_company()
    {
        $user = factory(User::class)->states('administrator')->create();
        $employee = factory(Employee::class)->create();

        $response = $this->actingAs($user)
                        ->from('companies.employees', $employee->company)
                        ->delete(route('companies.employees.destroy', [$employee->company, $employee]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id, 'first_name' => $employee->first_name, 'last_name' => $employee->last_name]);
    }

    /** @test */
    public function a_manager_can_delete_an_employee_from_a_company_they_belong_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $employee]);

        $response = $this->actingAs($user)
                        ->from('companies.employees.index', [$employee->company])
                        ->delete(route('companies.employees.destroy', [$employee->company, $employee]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('employees', ['id' => $employee->id, 'first_name' => $employee->first_name, 'last_name' => $employee->last_name]);
    }

    /** @test */
    public function a_manager_cannot_delete_an_employee_from_a_company_they_do_not_belong_to()
    {
        $employee = factory(Employee::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->from('companies.employees.index', $employee->company)
                        ->delete(route('companies.employees.destroy', [$employee->company, $employee]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'first_name' => $employee->first_name, 'last_name' => $employee->last_name]);
    }

    /** @test */
    public function a_guest_cannot_delete_an_employee()
    {
        $employee = factory(Employee::class)->create();

        $response = $this->delete(route('companies.employees.destroy', [$employee->company, $employee]));

        $response->assertRedirect('/login');
    }
}
