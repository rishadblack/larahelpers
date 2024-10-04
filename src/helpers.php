<?php

if (!function_exists('convertNumberToWordInEnglish')) {
    /**
     * Convert a numeric value to words in English.
     *
     * @param  mixed  $value
     * @param  bool|string  $sign
     * @return string
     */
    function convertNumberToWordInEnglish($value, $sign = false)
    {
        $value = preg_replace('/[^0-9.-]/', '', $value);

        if (!$sign) {
            $sign = ' TAKA ONLY';
        }

        if (empty($value)) {
            $value = 0;
        }

        $ones = [
            0 => 'ZERO',
            1 => 'ONE',
            2 => 'TWO',
            3 => 'THREE',
            4 => 'FOUR',
            5 => 'FIVE',
            6 => 'SIX',
            7 => 'SEVEN',
            8 => 'EIGHT',
            9 => 'NINE',
            10 => 'TEN',
            11 => 'ELEVEN',
            12 => 'TWELVE',
            13 => 'THIRTEEN',
            14 => 'FOURTEEN',
            15 => 'FIFTEEN',
            16 => 'SIXTEEN',
            17 => 'SEVENTEEN',
            18 => 'EIGHTEEN',
            19 => 'NINETEEN',
        ];
        $tens = [
            0 => 'ZERO',
            1 => 'TEN',
            2 => 'TWENTY',
            3 => 'THIRTY',
            4 => 'FORTY',
            5 => 'FIFTY',
            6 => 'SIXTY',
            7 => 'SEVENTY',
            8 => 'EIGHTY',
            9 => 'NINETY',
        ];

        $hundreds = [
            'HUNDRED',
            'THOUSAND',
            'MILLION',
            'BILLION',
            'TRILLION',
            'QUARDRILLION',
        ];

        /*limit t quadrillion */
        $num = number_format($value, 2, '.', ',');
        $num_arr = explode('.', $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(',', $wholenum));
        krsort($whole_arr, 1);
        $rettxt = '';
        foreach ($whole_arr as $key => $i) {
            while (substr($i, 0, 1) == '0') {
                $i = substr($i, 1, 5);
            }
            if ($i < 20) {
                /* echo "getting:".$i; */
                $rettxt .= isset($ones[$i]) ? $ones[$i] : '';
            } elseif ($i < 100) {
                if (substr($i, 0, 1) != '0') {
                    $rettxt .= $tens[substr($i, 0, 1)];
                }
                if (substr($i, 1, 1) != '0') {
                    $rettxt .= ' ' . $ones[substr($i, 1, 1)];
                }
            } else {
                if (substr($i, 0, 1) != '0') {
                    $rettxt .= $ones[substr($i, 0, 1)] . ' ' . $hundreds[0];
                }
                if (substr($i, 1, 1) != '0') {
                    $rettxt .= ' ' . $tens[substr($i, 1, 1)];
                }
                if (substr($i, 2, 1) != '0') {
                    $rettxt .= ' ' . $ones[substr($i, 2, 1)];
                }
            }
            if ($key > 0) {
                $rettxt .= ' ' . $hundreds[$key] . ' ';
            }
        }

        if ($decnum > 0) {
            $rettxt .= ' AND ';
            if ($decnum < 10) {
                if (substr($decnum, 0, 1) == 0) {
                    $rettxt .= $ones[substr($decnum, 0, 1)];
                    $rettxt .= ' ' . $ones[substr($decnum, 1, 1)];
                } else {
                    $rettxt .= $ones[$decnum];
                }
            } elseif ($decnum < 20) {
                $rettxt .= $ones[$decnum];
            } elseif ($decnum < 100) {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= ' ' . $ones[substr($decnum, 1, 1)];
            }
        }

        return $sign ? $rettxt . $sign : $rettxt;
    }
}

if (!function_exists('getBanglaNumbers')) {
    /**
     * Convert Arabic numerals to Bangla numerals.
     *
     * @param  mixed  $value
     * @return string
     */
    function getBanglaNumbers(): array
    {
        return [
            0 => 'শূন্য', 1 => 'এক', 2 => 'দুই', 3 => 'তিন', 4 => 'চার', 5 => 'পাঁচ',
            6 => 'ছয়', 7 => 'সাত', 8 => 'আট', 9 => 'নয়', 10 => 'দশ',
            11 => 'এগারো', 12 => 'বারো', 13 => 'তেরো', 14 => 'চৌদ্দ', 15 => 'পনের',
            16 => 'ষোল', 17 => 'সতেরো', 18 => 'আঠার', 19 => 'ঊনিশ', 20 => 'বিশ',
            21 => 'একুশ', 22 => 'বাইশ', 23 => 'তেইশ', 24 => 'চব্বিশ', 25 => 'পঁচিশ',
            26 => 'ছাব্বিশ', 27 => 'সাতাশ', 28 => 'আঠাশ', 29 => 'ঊনত্রিশ', 30 => 'ত্রিশ',
            31 => 'একত্রিশ', 32 => 'বত্রিশ', 33 => 'তেত্রিশ', 34 => 'চৌত্রিশ', 35 => 'পঁয়ত্রিশ',
            36 => 'ছত্রিশ', 37 => 'সাইত্রিশ', 38 => 'আটত্রিশ', 39 => 'ঊনচল্লিশ', 40 => 'চল্লিশ',
            41 => 'একচল্লিশ', 42 => 'বিয়াল্লিশ', 43 => 'তেতাল্লিশ', 44 => 'চুয়াল্লিশ', 45 => 'পঁয়তাল্লিশ',
            46 => 'ছেচল্লিশ', 47 => 'সাতচল্লিশ', 48 => 'আটচল্লিশ', 49 => 'ঊনপঞ্চাশ', 50 => 'পঞ্চাশ',
            51 => 'একান্ন', 52 => 'বায়ান্ন', 53 => 'তিপ্পান্ন', 54 => 'চুয়ান্ন', 55 => 'পঞ্চান্ন',
            56 => 'ছাপ্পান্ন', 57 => 'সাতান্ন', 58 => 'আটান্ন', 59 => 'ঊনষাট', 60 => 'ষাট',
            61 => 'একষট্টি', 62 => 'বাষট্টি', 63 => 'তেষট্টি', 64 => 'চৌষট্টি', 65 => 'পঁয়ষট্টি',
            66 => 'ছেষট্টি', 67 => 'সাতষট্টি', 68 => 'আটষট্টি', 69 => 'ঊনসত্তর', 70 => 'সত্তর',
            71 => 'একাত্তর', 72 => 'বাহাত্তর', 73 => 'তিয়াত্তর', 74 => 'চুয়াত্তর', 75 => 'পঁচাত্তর',
            76 => 'ছিয়াত্তর', 77 => 'সাতাত্তর', 78 => 'আটাত্তর', 79 => 'ঊনআশি', 80 => 'আশি',
            81 => 'একাশি', 82 => 'বিরাশি', 83 => 'তিরাশি', 84 => 'চুরাশি', 85 => 'পঁচাশি',
            86 => 'ছিয়াশি', 87 => 'সাতাশি', 88 => 'আটাশি', 89 => 'ঊননব্বই', 90 => 'নব্বই',
            91 => 'একানব্বই', 92 => 'বিরানব্বই', 93 => 'তিরানব্বই', 94 => 'চুরানব্বই', 95 => 'পঁচানব্বই',
            96 => 'ছিয়ানব্বই', 97 => 'সাতানব্বই', 98 => 'আটানব্বই', 99 => 'নিরানব্বই',
            100 => 'একশত',
        ];
    }
}

if (!function_exists('convertNumberToWordInBangla')) {
    /**
     * Convert a numeric value to words in Bangla.
     *
     * @param  mixed  $value
     * @param  bool|string  $sign
     * @return string
     */
    function convertNumberToWordInBangla(string | int $number, bool $isPoysha = true): string
    {
        // Get the associative array for Bangla numbers
        $ones = getBanglaNumbers();
        $words = '';

        // Split the number into integer and decimal parts
        if (strpos($number, '.') !== false) {
            list($integerPart, $decimalPart) = explode('.', $number);
        } else {
            $integerPart = $number;
            $decimalPart = '';
        }

        // Convert integer part to an integer for easier calculations
        $integerPart = intval($integerPart);

        // Break the integer part into crore, lakh, thousand, hundred, and remaining digits
        $crore = intval($integerPart / 10000000); // For Crores
        $lakh = intval(($integerPart % 10000000) / 100000); // For Lakhs
        $thousand = intval(($integerPart % 100000) / 1000); // For Thousands
        $hundred = intval(($integerPart % 1000) / 100); // For Hundreds
        $lastTwoDigits = intval($integerPart % 100); // Remaining digits (up to 99)

        // Build the words for the integer part
        if ($crore > 0) {
            $words .= ($ones[$crore] ?? '') . ' কোটি ';
        }
        if ($lakh > 0) {
            $words .= ($ones[$lakh] ?? '') . ' লাখ ';
        }
        if ($thousand > 0) {
            $words .= ($ones[$thousand] ?? '') . ' হাজার ';
        }
        if ($hundred > 0) {
            $words .= ($ones[$hundred] ?? '') . ' শো ';
        }

        // Handle the last two digits (units and tens)
        if ($lastTwoDigits > 0) {
            $words .= ($ones[$lastTwoDigits] ?? '');
        }

        // Handle the decimal (fractional) part
        if ($decimalPart !== '') {
            if ($isPoysha) {
                $decimalNumber = intval($decimalPart);
                $decimalWords = '';

                // If there are two decimal digits, convert directly to words
                if (strlen($decimalPart) == 2) {
                    $decimalWords = $ones[$decimalNumber] ?? '';
                } else {
                    // For single decimal digit, pad to two digits
                    $decimalDigits = str_pad($decimalPart, 2, '0', STR_PAD_RIGHT);
                    $decimalWords = $ones[intval($decimalDigits)] ?? '';
                }

                // Add "পয়সা" after decimal part
                if ($decimalWords !== '') {
                    $words .= ' টাকা ' . $decimalWords . ' পয়সা ';
                }
            } else {
                // For regular decimal (not পয়সা), convert each digit individually
                $decimalDigits = str_split($decimalPart);
                $words .= ' দশমিক ';
                foreach ($decimalDigits as $digit) {
                    $words .= ($ones[intval($digit)] ?? '') . ' ';
                }
            }
        } else {
            // If no decimal part, just add "টাকা"
            $words .= ' টাকা ';
        }

        // Add "মাত্র" at the end to indicate completion
        $words .= 'মাত্র';

        // Return the final Bangla words for the number
        return trim($words);
    }
}
