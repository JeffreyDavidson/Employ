<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Company;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'telephone' => rand(1111111111, 9999999999),
        'company_id' => factory(Company::class),
    ];
});
