<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states('administrator')->create(['email' => 'admin@site.com', 'password' => 'password']);
        factory(User::class)->states('manager')->create(['email' => 'manager@site.com', 'password' => 'password']);
    }
}
