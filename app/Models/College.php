<?php

namespace App\Models;

use Database\Factories\CollegeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
class College extends Model
{
    /** @use HasFactory<CollegeFactory> */
    use HasFactory;

    /**
     * @return HasMany<Program, $this>
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * Counselors assigned to this college.
     *
     * @return BelongsToMany<User, $this>
     */
    public function counselors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'college_counselor')->withTimestamps();
    }
}
