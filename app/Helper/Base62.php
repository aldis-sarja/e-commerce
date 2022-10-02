<?php

namespace App\Helper;

class Base62
{
    private static array $base62CodeTable = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    public static function encode(int $number): string
    {
        if ($number === 0) {
            return "0";
        }

        $res = "";

        while ($number > 0) {
            $res = self::$base62CodeTable[$number % 62] . $res;
            $number = intdiv($number, 62);
        }
        return $res;
    }
}
