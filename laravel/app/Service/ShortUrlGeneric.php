<?php


namespace App\Service;


use Illuminate\Support\Str;

class ShortUrlGeneric
{
    const MIN_LENGTH = 3;
    const MAX_LENGTH = 8;

    /**
     * @throws \Exception
     */
    public static function generate(): string
    {
        return Str::lower(Str::random(random_int(self::MIN_LENGTH, self::MAX_LENGTH)));
    }
}
