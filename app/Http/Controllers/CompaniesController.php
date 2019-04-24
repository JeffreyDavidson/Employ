<?php

namespace App\Http\Controllers;

use App\Company;

class CompaniesController extends Controller
{
    public function index()
    {
        $this->authorize('viewList', Company::class);

        $companies = Company::paginate(10);
        
        return view('companies.index', compact('companies'));
    }
}
