<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use App\Http\Requests\StoreEmployeeRequest;

class EmployeesController extends Controller
{
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
}
