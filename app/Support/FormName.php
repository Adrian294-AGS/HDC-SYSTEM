<?php

namespace App\Support;

final class FormName
{
    /**
     * Convert a dot-notation field name to an HTML input name: "parents.father.name" => "parents[father][name]".
     */
    public static function html(string $dotName): string
    {
        $parts = explode('.', $dotName);
        $first = array_shift($parts);

        return $first.implode('', array_map(fn (string $part) => "[{$part}]", $parts));
    }

    /**
     * Current value of a field for Alpine state: the old input after a failed submit, else the saved value.
     * Booleans become "1"/"0" so they match radio values.
     */
    public static function current(string $dotName, mixed $saved): mixed
    {
        $value = session()->hasOldInput() ? old($dotName) : $saved;

        if (is_array($value)) {
            return array_map('strval', $value);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $value === null ? '' : (string) $value;
    }
}
