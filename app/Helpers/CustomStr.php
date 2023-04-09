<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CustomStr
{
    public static function ulid(): string
    {
        return strtolower((string) Str::ulid());
    }
}
