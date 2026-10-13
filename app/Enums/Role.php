<?php

namespace App\Enums;

enum Role: string
{
    case Student = 'student';
    case Counselor = 'counselor';
    case Director = 'director';

    /**
     * Human-readable label for display in the UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Student => 'Student',
            self::Counselor => 'Counselor',
            self::Director => 'Director',
        };
    }

    /**
     * Name of the route for this role's dashboard.
     */
    public function dashboardRoute(): string
    {
        return $this->value.'.dashboard';
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
