<?php


namespace App\Validator;


use Illuminate\Support\Facades\Validator;

class LinkValidator
{
    const RULES_FOR_CREATE = [
        'long_url'  => 'string|required|url',
        'short_url' => 'string|required',
        'title'     => 'string|max:200',
        'tags'      => 'array',
    ];

    const RULES_FOR_UPDATE = [
        'long_url' => 'url',
        'title'    => 'string|max:200',
        'tags'     => 'array',
    ];

    public static function createValidation(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, self::RULES_FOR_CREATE);
    }

    public static function updateValidation(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, self::RULES_FOR_UPDATE);
    }
}
