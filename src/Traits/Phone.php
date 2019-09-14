<?php

namespace Helldar\BlacklistServer\Traits;

use Illuminate\Support\Str;

use function preg_replace;
use function str_ireplace;

class Phone
{
    protected function clear(string $phone = null): string
    {
        $phone = $this->convertWords($phone);

        return (string) preg_replace("/\D/", '', $phone);
    }

    private function convertWords(string $phone = null): string
    {
        $phone   = Str::lower($phone);
        $replace = [
            '2' => ['a', 'b', 'c'],
            '3' => ['d', 'e', 'f'],
            '4' => ['g', 'h', 'i'],
            '5' => ['j', 'k', 'l'],
            '6' => ['m', 'n', 'o'],
            '7' => ['p', 'q', 'r', 's'],
            '8' => ['t', 'u', 'v'],
            '9' => ['w', 'x', 'y', 'z'],
        ];
        foreach ($replace as $digit => $letters) {
            $phone = str_ireplace($letters, $digit, $phone);
        }

        return (string) $phone;
    }
}
