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

    /**
     * Checks to see if the user has the
     * ability to create a company.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Checks to see if the user has the
     * ability to update a company.
     *
     * @param  \App\User  $user
     * @param  \App\Company  $company
     * @return bool
     */
    public function update(User $user, Company $company)
    {
        return $user->isAdmin() || $company->managers->contains($user);
    }

    /**
     * Checks to see if the user has the
     * ability to view a company.
     *
     * @param  \App\User  $user
     * @param  \App\Company  $company
     * @return bool
     */
    public function view(User $user, Company $company)
    {
        return $user->isAdmin() || $company->managers->contains($user);
    }

    /**
     * Checks to see if the user has the
     * ability to delete a company.
     *
     * @param  \App\User  $user
     * @param  \App\Company  $company
     * @return bool
     */
    public function delete(User $user, Company $company)
    {
        return $user->isAdmin();
    }

    /**
     * Checks to see if the user has the
     * ability to delete a company.
     *
     * @param  \App\User  $user
     * @param  \App\Company  $company
     * @return bool
     */
    public function addEmployees(User $user, Company $company)
    {
        return $user->isAdmin() || $company->managers->contains($user);
    }
}
