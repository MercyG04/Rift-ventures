<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait EnumHelpers
{
    /**
     * Returns an array of the values (the strings) of the enum cases.
     * Example: ['local', 'international']
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Returns a collection of the cases, keyed by their value.
     */
    public static function asSelectArray(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn ($enum) => [$enum->value => $enum->name]);
    }
}