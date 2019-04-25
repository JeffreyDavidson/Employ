<?php

namespace Tests\Unit;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_an_administrator()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function a_user_can_be_a_manager()
    {
        $user = factory(User::class)->states('manager')->create();

        $this->assertTrue($user->isManager());
    }
}
