<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompaniesController extends Controller
{
    /**
     * Retrieve companies from system.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewList', Company::class);

        $companies = Company::paginate(10);

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a company.
     *
     * @return \Illuminate\View\View
     */
    public function create(Company $company)
    {
        $this->authorize('create', Company::class);

        return view('companies.create', compact('company'));
    }

    /**
     * Save the company to the database.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo')->store('public');
            $company->update(['logo' => $file]);
        }

        return redirect()->route('companies.index');
    }

    /**
     * Show the form for creating a company.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        return view('companies.edit', compact('company'));
    }

    /**
     * Save the company to the database.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo')->store('public');
            $company->update(['logo' => $file]);
        }

        return redirect()->route('companies.index');
    }
}
