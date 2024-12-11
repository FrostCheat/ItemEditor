<?php

namespace frostcheat\itemeditor\utils;

class Utils
{
    public static function numberToRoman(int $number): string {
        $string = [
            1 => "I",
            2 => "II",
            3 => "III",
            4 => "IV",
            5 => "V",
            6 => "VI",
            7 => "VII",
            8 => "VIII",
            9 => "IX",
            10 => "X",
        ];

        return $string[$number];
    }

}