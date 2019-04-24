<?php

namespace App\Policies;

use App\User;
use App\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Checks to see if the user has the ability
     * to view the list of all companies.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function viewList(User $user)
    {
        return $user->isAdmin();
    }
}
