<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCompanyTest extends TestCase
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
        $image = UploadedFile::fake()->image('logo.jpg');

        return array_replace([
            'name' => 'Example Company Name',
            'email' => 'info@company.com',
            'logo' => $overrides['logo'] ?? $image,
            'website' => 'companyname.com',
        ], $overrides);
    }

    /**
     * Old Attributes to assign to Model.
     *
     * @param  array  $overrides
     * @return array
     */
    protected function oldAttributes($overrides = [])
    {
        return array_replace([
            'name' => 'Old Company Name',
            'email' => 'oldemail@company.com',
            'logo' => 'oldlogo.jpg',
            'website' => 'oldcompanyname.com',
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_editing_a_company()
    {
        $user = factory(User::class)->states('administrator')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.edit', $company));

        $response->assertStatus(200);
        $response->assertViewIs('companies.edit');
    }

    /** @test */
    public function a_manager_can_view_the_form_for_editing_a_company_they_are_assigned_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);

        $response = $this->actingAs($user)
                        ->get(route('companies.edit', $company));

        $response->assertStatus(200);
    }

    /** @test */
    public function a_manager_cannot_view_the_form_for_editing_a_company_they_are_not_assigned_to()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.edit', $company));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->get(route('companies.edit', $company));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_administrator_can_update_a_company()
    {
        Storage::fake('public');

        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();
        $logo = UploadedFile::fake()->image('logo.jpg');

        $response = $this->actingAs($user)
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => $logo,
                        ]));

        tap($company->fresh(), function ($company) use ($logo) {
            $this->assertEquals('Example Company Name', $company->name);
            $this->assertEquals('info@company.com', $company->email);
            $this->assertEquals('public/' . $logo->hashName(), $company->logo);
            $this->assertEquals('companyname.com', $company->website);
        });

        Storage::disk('local')->assertExists('public/' . $logo->hashName());
    }

    /** @test */
    public function a_manager_can_update_a_company_they_are_assigned_to()
    {
        Storage::fake('public');

        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('manager')->create(['company_id' => $company]);
        $logo = UploadedFile::fake()->image('logo.jpg');

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => $logo,
                        ]));

        tap($company->fresh(), function ($company) use ($logo) {
            $this->assertEquals('Example Company Name', $company->name);
            $this->assertEquals('info@company.com', $company->email);
            $this->assertEquals('public/' . $logo->hashName(), $company->logo);
            $this->assertEquals('companyname.com', $company->website);
        });

        Storage::disk('local')->assertExists('public/' . $logo->hashName());
    }

    /** @test */
    public function a_manager_cannot_update_a_company_they_are_not_assigned_to()
    {
        $company = factory(Company::class)->create($this->oldAttributes());
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->patch(route('companies.update', $company), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_update_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->patch(route('companies.update', $company), $this->validParams());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function company_name_is_required()
    {
        $user = factory(User::class)->states('administrator')->create();
        $company = factory(Company::class)->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'name' => '',
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function company_name_must_be_a_string()
    {
        $user = factory(User::class)->states('administrator')->create();
        $company = factory(Company::class)->create($this->oldAttributes());

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'name' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function company_email_is_not_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'email' => '',
                        ]));

        $response->assertSessionHasNoErrors('email');
    }

    /** @test */
    public function company_email_must_be_a_string_when_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'email' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function company_email_must_be_a_valid_email_when_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'email' => 'not-a-valid-email',
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function company_logo_is_not_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => '',
                        ]));

        $response->assertSessionHasNoErrors('logo');
    }

    /** @test */
    public function company_logo_must_be_an_image_if_provided()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => 'not-an-image',
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function company_logo_must_be_at_most_100_pixels_wide()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => UploadedFile::fake()->image('logo.jpg', 101, 100),
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function company_logo_must_be_at_most_100_pixels_tall()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'logo' => UploadedFile::fake()->image('logo.jpg', 100, 101),
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('logo');
    }

    /** @test */
    public function company_website_is_not_required()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'website' => '',
                        ]));

        $response->assertSessionHasNoErrors('website');
    }

    /** @test */
    public function company_website_must_be_a_string_if_present()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.edit', $company))
                        ->patch(route('companies.update', $company), $this->validParams([
                            'website' => ['not-a-string'],
                        ]));

        $response->assertRedirect(route('companies.edit', $company));
        $response->assertSessionHasErrors('website');
    }
}
