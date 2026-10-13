<?php

namespace App\Enums;

enum RecordStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Reviewed = 'reviewed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Reviewed => 'Reviewed',
        };
    }

    /**
     * Tailwind classes for the status badge.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-amber-50 text-amber-800 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-300 dark:ring-amber-400/30',
            self::Submitted => 'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-300 dark:ring-blue-400/30',
            self::Reviewed => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-400/10 dark:text-green-300 dark:ring-green-400/30',
        };
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
