<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCompanyTest extends TestCase
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

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_a_company()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.create'));

        $response->assertStatus(200);
        $response->assertViewIs('companies.create');
    }

    /** @test */
    public function a_manager_cannot_view_the_form_for_creating_a_company()
    {
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->get(route('companies.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_a_company()
    {
        $response = $this->get(route('companies.create'));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_administrator_can_create_a_company()
    {
        Storage::fake('public');

        $user = factory(User::class)->states('administrator')->create();
        $logo = UploadedFile::fake()->image('logo.jpg');

        $response = $this->actingAs($user)
                        ->post(route('companies.store'), $this->validParams([
                            'logo' => $logo,
                        ]));

        tap(Company::first(), function ($company) use ($logo) {
            $this->assertEquals('Example Company Name', $company->name);
            $this->assertEquals('info@company.com', $company->email);
            $this->assertEquals('public/'. $logo->hashName(), $company->logo);
            $this->assertEquals('companyname.com', $company->website);
        });

        Storage::disk('local')->assertExists('public/'.$logo->hashName());
    }

    /** @test */
    public function an_manager_cannot_create_a_company()
    {
        $user = factory(User::class)->states('manager')->create();

        $response = $this->actingAs($user)
                        ->post(route('companies.store'), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function an_guest_cannot_create_a_company()
    {
        $response = $this->post(route('companies.store'), $this->validParams());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function company_name_is_required()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'name' => '',
                        ]));

        // dd($response);

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_name_must_be_a_string()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'name' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_email_is_not_required()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'email' => '',
                        ]));

        tap(Company::first(), function ($company) {
            $this->assertNull($company->email);
        });
    }

    /** @test */
    public function company_email_must_be_a_string_when_provided()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'email' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_email_must_be_a_valid_email_when_provided()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'email' => 'not-a-valid-email',
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_logo_is_not_required()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'logo' => '',
                        ]));

        tap(Company::first(), function ($company) {
            $this->assertNull($company->logo);
        });
    }

    /** @test */
    public function company_logo_must_be_an_image_if_provided()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'logo' => '',
                        ]));

        tap(Company::first(), function ($company) {
            $this->assertNull($company->logo);
        });
    }

    /** @test */
    public function company_logo_must_be_at_most_100_pixels_wide()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'logo' => UploadedFile::fake()->image('logo.jpg', 101, 100),
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('logo');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_logo_must_be_at_most_100_pixels_tall()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'logo' => UploadedFile::fake()->image('logo.jpg', 100, 101),
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('logo');
        $this->assertEquals(0, Company::count());
    }

    /** @test */
    public function company_website_is_not_required()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'website' => '',
                        ]));

        tap(Company::first(), function ($company) {
            $this->assertNull($company->website);
        });
    }

    /** @test */
    public function company_website_must_be_a_string_if_present()
    {
        $user = factory(User::class)->states('administrator')->create();

        $response = $this->actingAs($user)
                        ->from(route('companies.create'))
                        ->post(route('companies.store'), $this->validParams([
                            'website' => ['not-a-string'],
                        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('companies.create'));
        $response->assertSessionHasErrors('website');
        $this->assertEquals(0, Company::count());
    }
}
