<?php

namespace App\Policies;

use App\Models\CumulativeRecord;
use App\Models\User;

class CumulativeRecordPolicy
{
    /**
     * Students see only their own record. Staff see records of students in
     * colleges they may access (director: all; counselor: assigned colleges).
     */
    public function view(User $user, CumulativeRecord $record): bool
    {
        if ($user->isStudent()) {
            return $record->user_id === $user->id;
        }

        $collegeId = $record->student->program?->college_id;

        return $collegeId !== null && $user->accessibleCollegeIds()->contains($collegeId);
    }

    /**
     * Only students start their own Form 5.
     */
    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    /**
     * Only the owning student edits, and only until staff mark it reviewed.
     */
    public function update(User $user, CumulativeRecord $record): bool
    {
        return $user->isStudent()
            && $record->user_id === $user->id
            && $record->isEditableByStudent();
    }

    public function submit(User $user, CumulativeRecord $record): bool
    {
        return $this->update($user, $record);
    }
}
