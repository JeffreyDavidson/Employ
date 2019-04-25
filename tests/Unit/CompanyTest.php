<?php

namespace Tests\Unit;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_has_a_name()
    {
        $company = factory(Company::class)->create(['name' => 'Example Company Name']);

        $this->assertEquals('Example Company Name', $company->name);
    }

    /** @test */
    public function a_company_can_have_an_email()
    {
        $company = factory(Company::class)->create(['email' => 'info@company.com']);

        $this->assertEquals('info@company.com', $company->email);
    }

    /** @test */
    public function a_company_can_have_a_logo()
    {
        $company = factory(Company::class)->create(['logo' => 'companylogo.jpg']);

        $this->assertEquals('companylogo.jpg', $company->logo);
    }

    /** @test */
    public function a_company_can_have_a_website()
    {
        $company = factory(Company::class)->create(['website' => 'companyname.com']);

        $this->assertEquals('companyname.com', $company->website);
    }

    /** @test */
    public function a_company_can_have_many_managers()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $this->assertTrue($company->managers->contains($user));
    }
}
