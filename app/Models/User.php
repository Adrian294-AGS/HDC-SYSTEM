<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * `role` is intentionally NOT fillable: it is only ever set explicitly in code
 * (registration hardcodes Student; seeders and future admin screens set staff roles).
 */
#[Fillable(['name', 'last_name', 'first_name', 'middle_name', 'email', 'password', 'student_number', 'program_id', 'year_level'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'year_level' => 'integer',
        ];
    }

    /**
     * Keep the display `name` in sync with the separate name parts.
     */
    protected static function booted(): void
    {
        static::saving(function (User $user): void {
            if ($user->first_name || $user->last_name) {
                $user->name = collect([$user->first_name, $user->middle_name, $user->last_name])
                    ->filter()
                    ->implode(' ');
            }
        });
    }

    /**
     * The program a student is enrolled in.
     *
     * @return BelongsTo<Program, $this>
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Colleges a counselor is assigned to.
     *
     * @return BelongsToMany<College, $this>
     */
    public function colleges(): BelongsToMany
    {
        return $this->belongsToMany(College::class, 'college_counselor')->withTimestamps();
    }

    public function isStudent(): bool
    {
        return $this->role === Role::Student;
    }

    public function isCounselor(): bool
    {
        return $this->role === Role::Counselor;
    }

    public function isDirector(): bool
    {
        return $this->role === Role::Director;
    }

    public function hasRole(Role|string ...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->role === ($role instanceof Role ? $role : Role::tryFrom($role))) {
                return true;
            }
        }

        return false;
    }

    /**
     * IDs of the colleges whose student records this user may access.
     * Director: every college. Counselor: assigned colleges only. Student: none.
     *
     * @return Collection<int, int>
     */
    public function accessibleCollegeIds(): Collection
    {
        return match ($this->role) {
            Role::Director => College::query()->pluck('id'),
            Role::Counselor => $this->colleges()->pluck('colleges.id'),
            default => collect(),
        };
    }

    /**
     * Name of the route for this user's role dashboard.
     */
    public function dashboardRoute(): string
    {
        return $this->role->dashboardRoute();
    }

    /**
     * Limit a query to students whose program belongs to a college the viewer may access.
     * Use this on every staff-facing student query.
     *
     * @param  Builder<User>  $query
     */
    public function scopeStudentsVisibleTo(Builder $query, User $viewer): void
    {
        $query->where('role', Role::Student)
            ->whereHas('program', fn (Builder $program) => $program->whereIn('college_id', $viewer->accessibleCollegeIds()));
    }
}
