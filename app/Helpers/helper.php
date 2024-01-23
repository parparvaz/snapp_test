<?php

if (!function_exists('convertToEnglish')){
    function convertToEnglish($string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);

        return str_replace(array(...$persian, ...$arabic), array(...$num, ...$num), $string);
    }
}
