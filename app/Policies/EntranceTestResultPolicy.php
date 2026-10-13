<?php

namespace App\Policies;

use App\Models\EntranceTestResult;
use App\Models\User;

/**
 * SATA results: staff only, and only for students in colleges they may access.
 * Students can never view or encode SATA results.
 */
class EntranceTestResultPolicy
{
    /**
     * View a student's SATA history / encode a new result for them.
     */
    public function create(User $user, User $student): bool
    {
        return $student->isVisibleTo($user);
    }

    public function view(User $user, EntranceTestResult $result): bool
    {
        return $result->student->isVisibleTo($user);
    }

    public function update(User $user, EntranceTestResult $result): bool
    {
        return $this->view($user, $result);
    }
}
