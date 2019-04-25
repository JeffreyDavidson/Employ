<?php

namespace App\Policies;

use App\User;
use App\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Undocumented function
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return bool
     */
    public function update(User $user, Employee $employee)
    {
        return $user->isAdmin() || $user->isManager() && $employee->company->is($user->company);
    }

    /**
     * Undocumented function
     *
     * @param  \App\User  $user
     * @param  \App\Employee  $employee
     * @return bool
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->isAdmin() || $user->isManager() && $employee->company->is($user->company);
    }
}
