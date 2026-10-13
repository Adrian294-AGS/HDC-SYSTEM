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
            self::Draft => 'bg-amber-50 text-amber-800 ring-amber-600/20',
            self::Submitted => 'bg-blue-50 text-blue-700 ring-blue-700/10',
            self::Reviewed => 'bg-green-50 text-green-700 ring-green-600/20',
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
