<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeesController extends Controller
{
    public function index(Company $company)
    {
        $employees = $company->employees()->paginate(10);

        return view('employees.index', compact('company', 'employees'));
    }

    /**
     * Undocumented function
     *
     * @param  \App\Company  $company
     * @param  \App\Employee  $employee
     * @return void
     */
    public function create(Company $company, Employee $employee)
    {
        $this->authorize('addEmployees', $company);

        return view('employees.create', compact('company', 'employee'));
    }

    /**
     * Save the employee to the database.
     *
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEmployeeRequest $request, Company $company)
    {
        $employee = $company->employees()->create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'telephone' => $request->input('telephone'),
        ]);

        return redirect()->route('companies.employees.index', $company);
    }

    /**
     * Show the form for editing an employee.
     *
     * @param  \App\Company  $company
     * @param  \App\Employee  $employee
     * @return \Illuminate\View\View
     */
    public function edit(Company $company, Employee $employee)
    {
        $this->authorize('update', $employee);

        return view('employees.edit', compact('company', 'employee'));
    }

    /**
     * Update the given employee.
     *
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @param  \App\Company  $company
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, Company $company, Employee $employee)
    {
        $employee->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'telephone' => $request->input('telephone'),
        ]);

        return redirect()->route('companies.employees.index', $company);
    }
}
