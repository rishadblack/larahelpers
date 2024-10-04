<?php

use Illuminate\Support\Str;

if (!function_exists('matchRouteParameter')) {
    /**
     * Match a request parameter with a given data array.
     *
     * @param  array $Data The data to match against the request parameter.
     * @return bool True if the parameter matches; otherwise, false.
     */
    function matchRouteParameter($Data = [])
    {
        if (count($Data) == 0) {
            return false; // No data to match
        }

        $key = array_key_first($Data);

        if (request($key)) {
            $requestValue = (int) request($key);
            return $requestValue == $Data[$key]; // Return true if it matches
        }

        return false; // No match found
    }
}

if (!function_exists('switchColLang')) {
    /**
     * Switch column name based on the application's locale.
     *
     * @param  array $columns Associative array of language codes and their corresponding column names.
     * @return string The column name based on the current locale.
     */
    function switchColLang(array $columns)
    {
        $locale = app()->getLocale(); // Get the current application locale

        // Check if the locale has a corresponding column name
        if (array_key_exists($locale, $columns)) {
            return $columns[$locale]; // Return the column name for the current locale
        }

        // Return a default column name (fallback) if the locale is not found
        return $columns['en'] ?? ''; // Fallback to English or return an empty string
    }
}

if (!function_exists('perPageRows')) {
    /**
     * Get the number of rows to display per page.
     *
     * @param  array $Data An optional array of row options.
     * @return array The array of rows per page options.
     */
    function perPageRows($Data = [])
    {
        return count($Data) > 0 ? $Data : [10, 25, 50, 100, 250]; // Return default options if none provided
    }
}

if (!function_exists('addAllField')) {
    /**
     * Add an "All" option to a given data set.
     *
     * @param  mixed $Data The original data set.
     * @return array The original data set with "All" option added.
     */
    function addAllField($Data)
    {
        return count($Data) > 0 ? [null => 'All'] + $Data->toArray() : [null => 'All']; // Add "All" option
    }
}

if (!function_exists('currencySymbol')) {
    /**
     * Get the default currency symbol.
     *
     * @return string The currency symbol.
     */
    function currencySymbol()
    {
        return config('app.currency_symbol') ?? '৳'; // Default currency symbol
    }
}

if (!function_exists('numberEnToBn')) {
    /**
     * Convert English numbers to Bangla numbers.
     *
     * @param  string $number The number to be converted.
     * @return string The number in Bangla format.
     */
    function numberEnToBn($number)
    {
        $bn = ["১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০"];
        $en = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
        return str_replace($en, $bn, $number); // Replace English numbers with Bangla equivalents
    }
}

if (!function_exists('asset_storage')) {
    /**
     * Get the storage asset URL.
     *
     * @param  string $path The path to the asset.
     * @return string The complete asset URL.
     */
    function asset_storage($path)
    {
        return asset('storage/' . $path); // Return full storage asset URL
    }
}

if (!function_exists('asset_favicon')) {
    /**
     * Get the favicon asset URL.
     *
     * @param  string|null $path Optional custom path for the favicon.
     * @return string The favicon asset URL.
     */
    function asset_favicon($path = null)
    {
        return asset($path ? $path : config('app.favicon')); // Return favicon URL
    }
}

if (!function_exists('asset_logo')) {
    /**
     * Get the logo asset URL.
     *
     * @param  string|null $path Optional custom path for the logo.
     * @return string The logo asset URL.
     */
    function asset_logo($path = null)
    {
        return asset($path ? $path : config('app.logo')); // Return logo URL or default logo
    }
}

if (!function_exists('asset_powered_logo')) {
    /**
     * Get the powered logo asset URL.
     *
     * @param  string|null $path Optional custom path for the powered logo.
     * @return string The powered logo asset URL.
     */
    function asset_powered_logo($path = null)
    {
        return asset($path ? $path : config('app.logo_powered')); // Return powered logo URL
    }
}

if (!function_exists('asset_dark_logo')) {
    /**
     * Get the dark logo asset URL.
     *
     * @param  string|null $path Optional custom path for the dark logo.
     * @return string The dark logo asset URL.
     */
    function asset_dark_logo($path = null): string
    {
        return asset($path ? $path : config('app.dark_logo')); // Return dark logo URL
    }
}

if (!function_exists('asset_profile_picture')) {
    /**
     * Get the default profile picture asset URL.
     *
     * @return string The profile picture asset URL.
     */
    function asset_profile_picture($path = null): string
    {
        return asset($path ? $path : config('app.default_profile_picture')); // Return default profile picture URL
    }
}

if (!function_exists('numberFormatConverted')) {
    /**
     * Format a number with optional sign, decimal places, and thousand separator.
     *
     * @param  mixed  $value    The value to be formatted.
     * @param  bool|string $sign    Optional sign to prepend to the formatted number.
     * @param  int|bool  $decimal   Number of decimal places to include.
     * @param  string  $thousand    The thousand separator to use.
     * @return string               Formatted number with optional sign and thousand separator.
     */
    function numberFormatConverted($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Default decimal places to 2 if not specified
        if (!$decimal) {
            $decimal = 2;
        }

        // Return formatted value with optional sign
        if ($sign) {
            return $sign . number_format((float) $value, $decimal);
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimal, '.', $thousand);
    }
}

if (!function_exists('percentFormat')) {
    /**
     * Format a number as a percentage, with optional decimal places and percent sign.
     *
     * @param  mixed  $value        The value to be formatted.
     * @param  int  $decimal        Number of decimal places to round to.
     * @param  string  $percentSign The percent sign to append (default is '%').
     * @return string               Formatted percentage.
     */
    function percentFormat($value, $decimal = 2, $percentSign = '%')
    {
        // Remove any non-numeric characters except decimal, negative sign, or percent
        $value = preg_replace('/[^0-9-.%]/', '', $value);

        // Return formatted percentage with percent sign
        if ($percentSign) {
            return round($value, $decimal) . $percentSign;
        }

        // Return rounded value without percent sign
        return round($value, $decimal);
    }
}

if (!function_exists('pointFormat')) {
    /**
     * Format a number with a specified decimal and optional sign.
     *
     * @param  mixed  $value   The value to be formatted.
     * @param  bool|string $sign    Whether to include a sign or the sign to use.
     * @param  int|bool  $decimal   Number of decimal places.
     * @param  string  $thousand    The thousand separator to use.
     * @return string             Formatted number with optional sign.
     */
    function pointFormat($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', $value);

        // Set default value to 0 if empty
        if (empty($value)) {
            $value = 0;
        }

        // Default decimal places to 2 if not specified
        if (!$decimal) {
            $decimal = 2;
        }

        // Check if a sign is needed and format accordingly
        if ($sign) {
            if (!is_string($sign)) {
                $sign = config('app.point_sign');
            }

            return number_format((float) $value, $decimal) . ' ' . $sign;
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimal, '.', $thousand);
    }
}

if (!function_exists('unitFormat')) {
    /**
     * Format a number with an optional unit.
     *
     * @param  mixed $value    The value to be formatted.
     * @param  mixed $unitId   Unit to append.
     * @param  int $decimal    Number of decimal places.
     * @return string          Formatted number with unit.
     */
    function unitFormat($value, $unitId = false, $decimal = 0)
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', $value);

        // Set default value to 0 if empty
        if (empty($value)) {
            $value = 0;
        }

        // Determine unit to append
        $unit = '';
        if ($unitId) {
            if (is_string($unitId)) {
                $unit = $unitId;
            } else {
                $unit = 'unit';
            }
        }

        // Return formatted number with unit
        return number_format((float) $value, $decimal) . $unit;
    }
}

if (!function_exists('numberFormat')) {
    /**
     * Format a number with optional sign and decimal places.
     *
     * @param  mixed  $value    The value to be formatted.
     * @param  bool|string $sign    Whether to include a sign or the sign to use.
     * @param  int|bool  $decimal   Number of decimal places.
     * @param  string  $thousand    The thousand separator to use.
     * @return string             Formatted number with optional sign.
     */
    function numberFormat($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', $value);

        // Set default value to 0 if empty
        if (empty($value)) {
            $value = 0;
        }

        // Default decimal places to 2 if not specified
        if (!$decimal) {
            $decimal = 2;
        }

        // Check if a sign is needed and format accordingly
        if ($sign) {
            if (!is_string($sign)) {
                $sign = currencySymbol();
            }

            return number_format((float) $value, $decimal) . ' ' . $sign;
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimal, '.', $thousand);
    }
}

if (!function_exists('numberFormatOrPercent')) {
    /**
     * Format a number or return it as a percentage.
     *
     * @param  mixed  $value   The value to be formatted.
     * @param  bool   $sign    Whether to include a sign for the number.
     * @param  bool   $decimal Whether to include decimal points.
     * @param  string $thousand The thousand separator to use.
     * @return string          Formatted number or percentage.
     */
    function numberFormatOrPercent($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove any non-numeric characters except for decimal, negative, and percent symbols
        $value = preg_replace('/[^0-9-.%]/', '', $value);

        // If value contains a percentage sign, return it as-is
        if (strpos($value, '%') !== false) {
            return $value;
        }

        // Format the number using a separate formatting function
        return numberFormat($value, $sign, $decimal, $thousand);
    }
}

if (!function_exists('getPercentOfValue')) {
    /**
     * Calculate the value of a percentage of a given amount.
     *
     * @param  float|string  $percentage    The percentage to calculate (can include '%').
     * @param  float        $amount        The amount to calculate the percentage of.
     * @param  bool         $percenSign    Indicates if the percentage includes a '%' sign.
     * @return float                      The calculated value of the percentage of the amount.
     */
    function getPercentOfValue($percentage, $amount, $percenSign = true)
    {
        // Remove '%' sign if present and calculate the percentage value of the amount
        if ($percenSign) {
            return ($amount / 100) * str_replace('%', '', $percentage);
        }

        return ($amount / 100) * $percentage; // Calculate directly if no '%' sign is present
    }
}

if (!function_exists('getValueOfPercent')) {
    /**
     * Calculate the profit margin percentage based on profit and amount.
     *
     * @param  float  $profit   The total profit amount.
     * @param  float  $amount   The original amount to compare against.
     * @return float            The profit margin percentage.
     */
    function getValueOfPercent($profit, $amount)
    {
        $profitAmount = $profit - $amount;

        // Calculate the profit margin if profitAmount is not zero
        if ($profitAmount !== 0) {
            $totalProfitMargin = ($profitAmount / $amount) * 100;
        } else {
            $totalProfitMargin = 0.00; // No profit margin if profitAmount is zero
        }

        return $totalProfitMargin;
    }
}

if (!function_exists('getTimeFormat')) {
    /**
     * Get a specific date format based on the provided format index.
     *
     * @param  int|null  $timeFormat  The index for the desired date format.
     * @return string                 The date format string.
     */
    function getTimeFormat($timeFormat = null)
    {
        switch ($timeFormat) {
            case 1:
                return 'F j, Y';
            case 2:
                return 'D F j, Y';
            case 3:
                return 'D M j Y';
            case 4:
                return 'j, n, Y';
            case 5:
                return 'j/n/Y';
            case 6:
                return 'd, m, Y';
            case 7:
                return 'd/m/Y';
            case 8:
                return 'd-m-Y';
            case 9:
                return 'd-m-y';
            default:
                return 'd-m-Y h:i A'; // Default format
        }
    }
}

if (!function_exists('getTimeFormatJs')) {
    /**
     * Get the JavaScript-compatible date format by modifying the PHP date format.
     *
     * @return string  The modified date format string for JavaScript.
     */
    function getTimeFormatJs()
    {
        $getTimeFormat = getTimeFormat();

        // Replace PHP date format characters for JavaScript compatibility
        $getTimeFormat = Str::replace('g', 'h', $getTimeFormat);
        $getTimeFormat = Str::replace('G', 'H', $getTimeFormat);
        $getTimeFormat = Str::replace('a', 'K', $getTimeFormat);
        $getTimeFormat = Str::replace('A', 'K', $getTimeFormat);

        return $getTimeFormat;
    }
}

if (!function_exists('getfirstAndLastName')) {
    /**
     * Get the first or last name from a full name string.
     *
     * @param  string  $name      The full name.
     * @param  string  $callBack  Specify 'first' for the first name or anything else for the last name.
     * @return string             The requested name part.
     */
    function getfirstAndLastName($name, $callBack)
    {
        $splitName = explode(' ', $name, 2);

        if ($callBack == 'first') {
            return !empty($splitName[1]) ? $splitName[0] : '';
        } else {
            return !empty($splitName[1]) ? $splitName[1] : $splitName[0];
        }
    }
}

if (!function_exists('getFolderSize')) {
    /**
     * Calculate the total size of a folder and its contents.
     *
     * @param  string  $dir  The directory path to calculate the size of.
     * @return int          The total size in bytes.
     */
    function getFolderSize($dir)
    {
        $total_size = 0; // Initialize total size
        $dir_array = scandir($dir); // Get list of files and directories in the given directory

        foreach ($dir_array as $filename) {
            if ($filename !== '..' && $filename !== '.') { // Skip parent and current directory references
                $path = $dir . '/' . $filename; // Full path to the file or directory
                if (is_dir($path)) {
                    $total_size += getFolderSize($path); // Recursively get folder size
                } elseif (is_file($path)) {
                    $total_size += filesize($path); // Add file size to total
                }
            }
        }

        return $total_size; // Return total size in bytes
    }
}

if (!function_exists('getFormatSize')) {
    /**
     * Convert a size in bytes to a human-readable format (B, KB, MB, GB, TB).
     *
     * @param  int $bytes The size in bytes.
     * @return string Formatted size string.
     */
    function getFormatSize($bytes)
    {
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($bytes < 0) {
            return 'Invalid size'; // Handle negative byte sizes
        } elseif ($bytes < $kb) {
            return $bytes . ' B'; // Bytes
        } elseif ($bytes < $mb) {
            return ceil($bytes / $kb) . ' KB'; // Kilobytes
        } elseif ($bytes < $gb) {
            return ceil($bytes / $mb) . ' MB'; // Megabytes
        } elseif ($bytes < $tb) {
            return ceil($bytes / $gb) . ' GB'; // Gigabytes
        } else {
            return ceil($bytes / $tb) . ' TB'; // Terabytes
        }
    }
}

if (!function_exists('getCheckDevice')) {
    /**
     * Check the user's device type based on the user agent string.
     *
     * @return int|null Returns 1 for Android, 2 for iOS, 3 for Windows, or null if not matched.
     */
    function getCheckDevice()
    {
        $userAgent = request()->server('HTTP_USER_AGENT');

        // Default to null if no match found
        if ($userAgent === 'app-android') {
            return 1; // Android
        } elseif ($userAgent === 'app-ios') {
            return 2; // iOS
        } elseif ($userAgent === 'app-windows') {
            return 3; // Windows
        }

        return null; // Return null if no match found
    }
}

if (!function_exists('getGenerateDepth')) {
    /**
     * Generate a string of indentation characters based on the specified depth.
     *
     * @param  int    $depth  The number of indentation levels.
     * @param  string $sign   The character to use for indentation (default is '-').
     * @return string         A string of indentation characters.
     */
    function getGenerateDepth($depth, $sign = '-')
    {
        $prefix = str_repeat($sign, $depth);
        return $prefix;
    }
}

if (!function_exists('convertPipeToArray')) {
    /**
     * Convert a pipe-separated string into an array, handling quotes.
     *
     * @param  string  $pipeString  The input string to convert.
     * @param  string  $separator    Optional custom separator (default is '|').
     * @return array|string          An array of elements or the original string if too short.
     */
    function convertPipeToArray(string $pipeString, string $separator = '|')
    {
        $pipeString = trim($pipeString);

        // Return the original string if its length is 2 or less.
        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        // Get the first and last characters
        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($pipeString, -1, 1);

        // Check if the string starts and ends with the same quote character
        if ($quoteCharacter === $endCharacter && in_array($quoteCharacter, ["'", '"'])) {
            // Remove the surrounding quotes and split using the specified separator
            return explode($separator, trim($pipeString, $quoteCharacter));
        }

        // If not quoted, split the string directly using the specified separator
        return explode($separator, $pipeString);
    }
}

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