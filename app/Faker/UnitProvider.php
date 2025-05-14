<?php

namespace App\Faker;

use Faker\Provider\Base;

class UnitProvider extends Base
{
    /**
     * @return array
     */
    public static function getUnits(): array
    {
        return [
            'g',
            'kg',
            'ml',
            'l',
            'tsp',
            'tbsp',
            'cup',
            'oz',
            'lb',
            'pt',
            'qt',
            'gal',
        ];
    }

    /**
     * @return string
     */
    public static function unitName(): string
    {
        return static::randomElement(static::getUnits());
    }
}
    