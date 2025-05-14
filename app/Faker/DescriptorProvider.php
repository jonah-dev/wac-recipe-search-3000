<?php

namespace App\Faker;

use Faker\Provider\Base;

class DescriptorProvider extends Base
{
    public static function getDescriptors(): array
    {
        return [
            'small',
            'medium',
            'large',
            'extra-large',
            'chopped',
            'sliced',
            'diced',
            'minced',
            'grated',
            'crushed',
        ];
    }

    public function descriptorName(): string
    {
        return static::randomElement(static::getDescriptors());
    }
}
