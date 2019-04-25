<?php

use App\Company;
use App\Employee;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Company::class, 50)
            ->create()
            ->each(function ($company) {
                $company->employees()->saveMany(factory(Employee::class, 50)->create());
            });
    }
}
