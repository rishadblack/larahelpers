<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

if (! function_exists('matchRouteParameter')) {
    /**
     * Match a request parameter with a given data array.
     *
     * @param  array  $Data  The data to match against the request parameter.
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

if (! function_exists('switchColLang')) {
    /**
     * Switch column name based on the application's locale.
     *
     * @param  array  $columns  Associative array of language codes and their corresponding column names.
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

if (! function_exists('perPageRows')) {
    /**
     * Get the number of rows to display per page.
     *
     * @param  array  $Data  An optional array of row options.
     * @return array The array of rows per page options.
     */
    function perPageRows($Data = [])
    {
        return count($Data) > 0 ? $Data : [10, 25, 50, 100, 250]; // Return default options if none provided
    }
}

if (! function_exists('addAllField')) {
    /**
     * Add an "All" option to a given data set.
     *
     * @param  mixed  $Data  The original data set.
     * @return array The original data set with "All" option added.
     */
    function addAllField($Data)
    {
        if ($Data instanceof Arrayable) {
            $Data = $Data->toArray();
        }

        return count($Data) > 0 ? [null => 'All'] + $Data : [null => 'All']; // Add "All" option
    }
}

if (! function_exists('currencySymbol')) {
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

if (! function_exists('numberEnToBn')) {
    /**
     * Convert English numbers to Bangla numbers.
     *
     * @param  string  $number  The number to be converted.
     * @return string The number in Bangla format.
     */
    function numberEnToBn($number)
    {
        $bn = ['১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০'];
        $en = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

        return str_replace($en, $bn, (string) $number); // Replace English numbers with Bangla equivalents
    }
}

if (! function_exists('numberBnToEn')) {
    /**
     * Convert Bangla digits to English digits, keeping every other character.
     *
     * @param  mixed  $number  The value containing Bangla digits.
     * @return string The value with English digits.
     */
    function numberBnToEn(mixed $number): string
    {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($bn, $en, (string) $number);
    }
}

if (! function_exists('compactNumber')) {
    /**
     * Abbreviate a number with a unit suffix, using the Bangladeshi lakh/crore system by default.
     *
     * @param  mixed  $value  The value to abbreviate.
     * @param  int  $decimals  Maximum decimals to keep; trailing zeros are removed.
     * @param  string  $system  'bd' for K / Lakh / Cr, 'international' for K / M / B / T.
     * @param  string  $locale  'en' for Latin output, 'bn' for Bangla digits and unit words.
     * @return string The abbreviated number, e.g. '1.5 Cr', '12 Lakh', '2.5K' or '১.৫ কোটি'.
     */
    function compactNumber(mixed $value, int $decimals = 1, string $system = 'bd', string $locale = 'en'): string
    {
        $value = (float) preg_replace('/[^0-9.-]/', '', (string) $value);

        $units = $system === 'bd'
            ? [10000000 => [' Cr', ' কোটি'], 100000 => [' Lakh', ' লাখ'], 1000 => ['K', ' হাজার']]
            : [1000000000000 => ['T', 'T'], 1000000000 => ['B', 'B'], 1000000 => ['M', 'M'], 1000 => ['K', 'K']];

        $suffix = '';

        foreach ($units as $divisor => [$englishUnit, $banglaUnit]) {
            if (abs($value) >= $divisor) {
                $value /= $divisor;
                $suffix = $locale === 'bn' ? $banglaUnit : $englishUnit;
                break;
            }
        }

        $formatted = number_format($value, $decimals, '.', '');

        if (str_contains($formatted, '.')) {
            $formatted = rtrim(rtrim($formatted, '0'), '.');
        }

        return ($locale === 'bn' ? numberEnToBn($formatted) : $formatted).$suffix;
    }
}

if (! function_exists('maskString')) {
    /**
     * Mask the middle of a string, keeping a number of characters visible at each end.
     *
     * @param  mixed  $value  The value to mask.
     * @param  int  $visibleStart  Characters to leave visible at the start.
     * @param  int  $visibleEnd  Characters to leave visible at the end.
     * @param  string  $mask  The masking character.
     * @return string The masked string, or the original when it is too short to mask.
     */
    function maskString(mixed $value, int $visibleStart = 0, int $visibleEnd = 4, string $mask = '*'): string
    {
        $value = (string) $value;
        $length = mb_strlen($value);

        if ($visibleStart + $visibleEnd >= $length) {
            return $value;
        }

        return mb_substr($value, 0, $visibleStart)
            .str_repeat($mask, $length - $visibleStart - $visibleEnd)
            .($visibleEnd > 0 ? mb_substr($value, -$visibleEnd) : '');
    }
}

if (! function_exists('initials')) {
    /**
     * Build upper case initials from a name, using the first and last words when the name is longer than the limit.
     *
     * @param  mixed  $name  The full name.
     * @param  int  $limit  Maximum number of initials.
     * @return string The initials, e.g. 'JD' for 'John Ronald Doe'.
     */
    function initials(mixed $name, int $limit = 2): string
    {
        $words = preg_split('/\s+/u', trim((string) $name), -1, PREG_SPLIT_NO_EMPTY);

        if ($words === [] || $limit < 1) {
            return '';
        }

        if (count($words) > $limit) {
            $words = $limit === 1
                ? [$words[0]]
                : [...array_slice($words, 0, $limit - 1), end($words)];
        }

        return implode('', array_map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)), $words));
    }
}

if (! function_exists('formatBdPhone')) {
    /**
     * Normalise a Bangladeshi mobile number written in any common form.
     *
     * Accepts '01712345678', '1712345678', '+8801712345678', '8801712345678' and '008801712345678',
     * with spaces or dashes anywhere.
     *
     * @param  mixed  $phone  The phone number to normalise.
     * @param  string  $format  'local' for 01712345678, 'international' for +8801712345678, 'dashed' for 01712-345678.
     * @return string|null The normalised number, or null when it is not a valid Bangladeshi mobile number.
     */
    function formatBdPhone(mixed $phone, string $format = 'local'): ?string
    {
        $digits = ltrim(preg_replace('/\D/', '', (string) $phone), '0');

        if (strlen($digits) === 13 && str_starts_with($digits, '880')) {
            $digits = substr($digits, 3);
        }

        if (! preg_match('/^1[3-9]\d{8}$/', $digits)) {
            return null;
        }

        return match ($format) {
            'local' => '0'.$digits,
            'international' => '+880'.$digits,
            'dashed' => '0'.substr($digits, 0, 4).'-'.substr($digits, 4),
            default => throw new InvalidArgumentException("Unknown phone format [{$format}]. Use local, international or dashed."),
        };
    }
}

if (! function_exists('isBdMobile')) {
    /**
     * Check whether a value is a valid Bangladeshi mobile number in any common form.
     *
     * @param  mixed  $phone  The phone number to check.
     */
    function isBdMobile(mixed $phone): bool
    {
        return formatBdPhone($phone) !== null;
    }
}

if (! function_exists('banglaDate')) {
    /**
     * Format a date with Bangla month and day names, meridiem and digits.
     *
     * @param  mixed  $date  Anything Carbon can parse; null means now.
     * @param  string|null  $format  A PHP date format; defaults to getTimeFormat().
     * @return string The formatted date, e.g. '০৭ অক্টোবর ২০২৬'.
     */
    function banglaDate(mixed $date = null, ?string $format = null): string
    {
        $formatted = Carbon::parse($date ?? Carbon::now())
            ->locale('bn')
            ->translatedFormat($format ?? getTimeFormat());

        return numberEnToBn($formatted);
    }
}

if (! function_exists('fiscalYear')) {
    /**
     * Resolve the fiscal year containing a date. Bangladesh runs July to June by default.
     *
     * @param  mixed  $date  Anything Carbon can parse; null means today.
     * @param  int|null  $startMonth  First month of the fiscal year (1–12); defaults to config('app.fiscal_year_start_month', 7).
     * @return array{label: string, start: Carbon, end: Carbon, start_year: int, end_year: int}
     */
    function fiscalYear(mixed $date = null, ?int $startMonth = null): array
    {
        $date = Carbon::parse($date ?? Carbon::now());
        $startMonth ??= (int) config('app.fiscal_year_start_month', 7);

        if ($startMonth < 1 || $startMonth > 12) {
            throw new InvalidArgumentException("Fiscal year start month must be between 1 and 12, [{$startMonth}] given.");
        }

        $startYear = $date->month >= $startMonth ? $date->year : $date->year - 1;
        $start = Carbon::create($startYear, $startMonth, 1)->startOfDay();
        $end = $start->copy()->addYear()->subDay()->endOfDay();
        $endYear = $startMonth === 1 ? $startYear : $startYear + 1;

        return [
            'label' => $startMonth === 1 ? (string) $startYear : $startYear.'-'.substr((string) $endYear, -2),
            'start' => $start,
            'end' => $end,
            'start_year' => $startYear,
            'end_year' => $endYear,
        ];
    }
}

if (! function_exists('asset_storage')) {
    /**
     * Get the storage asset URL.
     *
     * @param  string  $path  The path to the asset.
     * @return string The complete asset URL.
     */
    function asset_storage($path)
    {
        return asset('storage/'.$path); // Return full storage asset URL
    }
}

if (! function_exists('asset_favicon')) {
    /**
     * Get the favicon asset URL.
     *
     * @param  string|null  $path  Optional custom path for the favicon.
     * @return string The favicon asset URL.
     */
    function asset_favicon($path = null)
    {
        return asset($path ? $path : config('app.favicon')); // Return favicon URL
    }
}

if (! function_exists('asset_logo')) {
    /**
     * Get the logo asset URL.
     *
     * @param  string|null  $path  Optional custom path for the logo.
     * @return string The logo asset URL.
     */
    function asset_logo($path = null)
    {
        return asset($path ? $path : config('app.logo')); // Return logo URL or default logo
    }
}

if (! function_exists('asset_powered_logo')) {
    /**
     * Get the powered logo asset URL.
     *
     * @param  string|null  $path  Optional custom path for the powered logo.
     * @return string The powered logo asset URL.
     */
    function asset_powered_logo($path = null)
    {
        return asset($path ? $path : config('app.logo_powered')); // Return powered logo URL
    }
}

if (! function_exists('asset_dark_logo')) {
    /**
     * Get the dark logo asset URL.
     *
     * @param  string|null  $path  Optional custom path for the dark logo.
     * @return string The dark logo asset URL.
     */
    function asset_dark_logo($path = null): string
    {
        return asset($path ? $path : config('app.dark_logo')); // Return dark logo URL
    }
}

if (! function_exists('asset_profile_picture')) {
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

if (! function_exists('numberFormatConverted')) {
    /**
     * Format a number with optional sign, decimal places, and thousand separator.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  bool|string  $sign  Optional sign to prepend to the formatted number.
     * @param  int|bool  $decimal  Number of decimal places to include.
     * @param  string  $thousand  The thousand separator to use.
     * @return string Formatted number with optional sign and thousand separator.
     */
    function numberFormatConverted($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove non-numeric characters except for decimal and negative signs, like the other formatters
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

        if (empty($value)) {
            $value = 0;
        }

        // Default decimal places to 2 if not specified
        if (! $decimal) {
            $decimal = 2;
        }

        // Return formatted value with optional sign
        if ($sign) {
            return $sign.number_format((float) $value, $decimal);
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimal, '.', $thousand);
    }
}

if (! function_exists('percentFormat')) {
    /**
     * Format a number as a percentage, with optional decimal places and percent sign.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  int  $decimal  Number of decimal places to round to.
     * @param  string  $percentSign  The percent sign to append (default is '%').
     * @return string|float Formatted percentage, or the rounded number when no sign is given.
     */
    function percentFormat($value, $decimal = 2, $percentSign = '%')
    {
        // Remove any non-numeric characters, then drop the percent sign so the value can be rounded
        $value = (float) str_replace('%', '', preg_replace('/[^0-9-.%]/', '', (string) $value));

        // Return formatted percentage with percent sign
        if ($percentSign) {
            return round($value, $decimal).$percentSign;
        }

        // Return rounded value without percent sign
        return round($value, $decimal);
    }
}

if (! function_exists('pointFormat')) {
    /**
     * Format a number with a specified decimal and optional sign.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  bool|string  $sign  Whether to include a sign or the sign to use.
     * @param  int|bool  $decimal  Number of decimal places.
     * @param  string  $thousand  The thousand separator to use.
     * @return string Formatted number with optional sign.
     */
    function pointFormat($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

        // Set default value to 0 if empty
        if (empty($value)) {
            $value = 0;
        }

        // Default decimal places to 2 if not specified
        if (! $decimal) {
            $decimal = 2;
        }

        // Check if a sign is needed and format accordingly
        if ($sign) {
            if (! is_string($sign)) {
                $sign = config('app.point_sign');
            }

            return number_format((float) $value, $decimal).' '.$sign;
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimal, '.', $thousand);
    }
}

if (! function_exists('unitFormat')) {
    /**
     * Format a number with an optional unit.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  mixed  $unitId  Unit to append.
     * @param  int  $decimal  Number of decimal places.
     * @return string Formatted number with unit.
     */
    function unitFormat($value, $unitId = false, $decimal = 0)
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

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
        return number_format((float) $value, $decimal).$unit;
    }
}

if (! function_exists('numberFormat')) {
    /**
     * Format a number with optional sign and decimal places.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  bool|string  $sign  Whether to include a sign or the sign to use.
     * @param  int|bool  $decimal  Number of decimal places.
     * @param  string  $thousand  The thousand separator to use.
     * @return string Formatted number with optional sign.
     */
    function numberFormat($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove non-numeric characters except for decimal and negative signs
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

        // Set default value to 0 if empty
        if (empty($value)) {
            $value = 0;
        }

        // Determine decimal places
        if ($decimal === true) {
            // If decimal is true, show as integer (no decimals)
            $decimals = 0;
        } elseif (is_numeric($decimal)) {
            $decimals = (int) $decimal;
        } else {
            // Default decimals
            $decimals = 2;
        }

        // Check if a sign is needed and format accordingly
        if ($sign) {
            if (! is_string($sign)) {
                $sign = currencySymbol();
            }

            return number_format((float) $value, $decimals).' '.$sign;
        }

        // Return formatted number without sign
        return number_format((float) $value, $decimals, '.', $thousand);
    }
}

if (! function_exists('numberFormatOrPercent')) {
    /**
     * Format a number or return it as a percentage.
     *
     * @param  mixed  $value  The value to be formatted.
     * @param  bool  $sign  Whether to include a sign for the number.
     * @param  bool  $decimal  Whether to include decimal points.
     * @param  string  $thousand  The thousand separator to use.
     * @return string Formatted number or percentage.
     */
    function numberFormatOrPercent($value, $sign = false, $decimal = false, $thousand = '')
    {
        // Remove any non-numeric characters except for decimal, negative, and percent symbols
        $value = preg_replace('/[^0-9-.%]/', '', (string) $value);

        // If value contains a percentage sign, return it as-is
        if (strpos($value, '%') !== false) {
            return $value;
        }

        // Format the number using a separate formatting function
        return numberFormat($value, $sign, $decimal, $thousand);
    }
}

if (! function_exists('getPercentOfValue')) {
    /**
     * Calculate the value of a percentage of a given amount.
     *
     * @param  float|string  $percentage  The percentage to calculate (can include '%').
     * @param  float  $amount  The amount to calculate the percentage of.
     * @param  bool  $percenSign  Indicates if the percentage includes a '%' sign.
     * @return float The calculated value of the percentage of the amount.
     */
    function getPercentOfValue($percentage, $amount, $percenSign = true)
    {
        // Remove '%' sign if present and calculate the percentage value of the amount
        if ($percenSign) {
            return ($amount / 100) * (float) str_replace('%', '', (string) $percentage);
        }

        return ($amount / 100) * $percentage; // Calculate directly if no '%' sign is present
    }
}

if (! function_exists('getValueOfPercent')) {
    /**
     * Calculate the profit margin percentage based on profit and amount.
     *
     * @param  float  $profit  The total profit amount.
     * @param  float  $amount  The original amount to compare against.
     * @return float The profit margin percentage.
     */
    function getValueOfPercent($profit, $amount)
    {
        $profitAmount = $profit - $amount;

        // No margin without a profit difference or without an amount to compare against
        if ($profitAmount == 0 || $amount == 0) {
            return 0.0;
        }

        return ($profitAmount / $amount) * 100;
    }
}

if (! function_exists('getTimeFormat')) {
    /**
     * Get a specific date format based on the provided format index.
     *
     * @param  int|null  $timeFormat  The index for the desired date format.
     * @return string The date format string.
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

if (! function_exists('getTimeFormatJs')) {
    /**
     * Get the JavaScript-compatible date format by modifying the PHP date format.
     *
     * @param  int|null  $timeFormat  The same index accepted by getTimeFormat().
     * @return string The modified date format string for JavaScript.
     */
    function getTimeFormatJs($timeFormat = null)
    {
        $getTimeFormat = getTimeFormat($timeFormat);

        // Replace PHP date format characters for JavaScript compatibility
        $getTimeFormat = Str::replace('g', 'h', $getTimeFormat);
        $getTimeFormat = Str::replace('G', 'H', $getTimeFormat);
        $getTimeFormat = Str::replace('a', 'K', $getTimeFormat);
        $getTimeFormat = Str::replace('A', 'K', $getTimeFormat);

        return $getTimeFormat;
    }
}

if (! function_exists('getfirstAndLastName')) {
    /**
     * Get the first or last name from a full name string.
     *
     * @param  string  $name  The full name.
     * @param  string  $callBack  Specify 'first' for the first name or anything else for the last name.
     * @return string The requested name part.
     */
    function getfirstAndLastName($name, $callBack)
    {
        // Collapse repeated whitespace so a double space never leaks into the last name
        $splitName = explode(' ', trim(preg_replace('/\s+/u', ' ', (string) $name)), 2);

        if ($callBack == 'first') {
            return ! empty($splitName[1]) ? $splitName[0] : '';
        } else {
            return ! empty($splitName[1]) ? $splitName[1] : $splitName[0];
        }
    }
}

if (! function_exists('getFolderSize')) {
    /**
     * Calculate the total size of a folder and its contents.
     *
     * @param  string  $dir  The directory path to calculate the size of.
     * @return int The total size in bytes.
     */
    function getFolderSize($dir)
    {
        $total_size = 0;            // Initialize total size
        $dir_array = scandir($dir); // Get list of files and directories in the given directory

        foreach ($dir_array as $filename) {
            if ($filename !== '..' && $filename !== '.') { // Skip parent and current directory references
                $path = $dir.'/'.$filename;                // Full path to the file or directory
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

if (! function_exists('getFormatSize')) {
    /**
     * Convert a size in bytes to a human-readable format (B, KB, MB, GB, TB).
     *
     * @param  int  $bytes  The size in bytes.
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
            return $bytes.' B'; // Bytes
        } elseif ($bytes < $mb) {
            return ceil($bytes / $kb).' KB'; // Kilobytes
        } elseif ($bytes < $gb) {
            return ceil($bytes / $mb).' MB'; // Megabytes
        } elseif ($bytes < $tb) {
            return ceil($bytes / $gb).' GB'; // Gigabytes
        } else {
            return ceil($bytes / $tb).' TB'; // Terabytes
        }
    }
}

if (! function_exists('getCheckDevice')) {
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

if (! function_exists('getGenerateDepth')) {
    /**
     * Generate a string of indentation characters based on the specified depth.
     *
     * @param  int  $depth  The number of indentation levels.
     * @param  string  $sign  The character to use for indentation (default is '-').
     * @return string A string of indentation characters.
     */
    function getGenerateDepth($depth, $sign = '-')
    {
        $prefix = str_repeat($sign, $depth);

        return $prefix;
    }
}

if (! function_exists('convertPipeToArray')) {
    /**
     * Convert a pipe-separated string into an array, handling quotes.
     *
     * @param  string  $pipeString  The input string to convert.
     * @param  string  $separator  Optional custom separator (default is '|').
     * @return array|string An array of elements or the original string if too short.
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

if (! function_exists('convertIntegerToWordInEnglish')) {
    /**
     * Spell an integer in upper case English words.
     *
     * @param  int  $number  The integer to spell.
     * @param  string  $system  'international' for thousand/million/billion, 'bd' for thousand/lakh/crore.
     * @return string The words, e.g. 'TWELVE LAKH THIRTY FOUR THOUSAND' or 'ONE MILLION'.
     */
    function convertIntegerToWordInEnglish(int $number, string $system = 'international'): string
    {
        if ($number === 0) {
            return 'ZERO';
        }

        if ($number < 0) {
            return 'MINUS '.convertIntegerToWordInEnglish(-$number, $system);
        }

        $ones = [
            'ZERO', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE',
            'TEN', 'ELEVEN', 'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN',
        ];
        $tens = ['', '', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'];

        $belowThousand = function (int $value) use ($ones, $tens): string {
            $parts = [];

            if ($value >= 100) {
                $parts[] = $ones[intdiv($value, 100)].' HUNDRED';
                $value %= 100;
            }

            if ($value >= 20) {
                $parts[] = $tens[intdiv($value, 10)];
                $value %= 10;
            }

            if ($value > 0) {
                $parts[] = $ones[$value];
            }

            return implode(' ', $parts);
        };

        $scales = $system === 'bd'
            ? [10000000 => 'CRORE', 100000 => 'LAKH', 1000 => 'THOUSAND']
            : [
                1000000000000000 => 'QUADRILLION',
                1000000000000 => 'TRILLION',
                1000000000 => 'BILLION',
                1000000 => 'MILLION',
                1000 => 'THOUSAND',
            ];

        $words = [];

        foreach ($scales as $divisor => $label) {
            if ($number >= $divisor) {
                $count = intdiv($number, $divisor);
                $number %= $divisor;

                // Crore counts are unbounded ("ONE HUNDRED FIFTY CRORE", "TWO LAKH CRORE"), so spell them recursively
                $words[] = ($count >= 1000 ? convertIntegerToWordInEnglish($count, $system) : $belowThousand($count)).' '.$label;
            }
        }

        if ($number > 0) {
            $words[] = $belowThousand($number);
        }

        return implode(' ', $words);
    }
}

if (! function_exists('convertNumberToWordInEnglish')) {
    /**
     * Convert a numeric value to upper case English words, reading the first two decimals as a whole number.
     *
     * @param  mixed  $value  The amount; formatted strings such as '1,500.50' are accepted.
     * @param  bool|string  $sign  Suffix to append; false means ' TAKA ONLY'.
     * @return string e.g. 'ONE THOUSAND TWO HUNDRED THIRTY FOUR AND FIFTY TAKA ONLY'.
     */
    function convertNumberToWordInEnglish($value, $sign = false)
    {
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

        if (! $sign) {
            $sign = ' TAKA ONLY';
        }

        if ($value === '' || ! is_numeric($value)) {
            $value = 0;
        }

        $value = (float) $value;
        [$wholeNumber, $decimalNumber] = explode('.', number_format(abs($value), 2, '.', ''));

        $words = convertIntegerToWordInEnglish((int) $wholeNumber);

        if ((int) $decimalNumber > 0) {
            $words .= ' AND '.convertIntegerToWordInEnglish((int) $decimalNumber);
        }

        if ($value < 0 && $words !== 'ZERO') {
            $words = 'MINUS '.$words;
        }

        return $sign ? $words.$sign : $words;
    }
}

if (! function_exists('numberToWords')) {
    /**
     * Spell an amount in the current (or given) locale.
     *
     * English output uses the Bangladeshi lakh/crore system by default and reads the decimals as a minor
     * currency unit: 'ONE THOUSAND TWO HUNDRED THIRTY FOUR TAKA AND FIFTY PAISA ONLY'. With $currency false
     * the decimals are read digit by digit: 'ONE THOUSAND TWO HUNDRED THIRTY FOUR POINT FIVE'.
     * Bangla output is produced by convertNumberToWordInBangla().
     *
     * @param  mixed  $value  The amount; formatted strings such as '1,500.50' are accepted.
     * @param  string|null  $locale  'en' or 'bn'; null uses app()->getLocale().
     * @param  bool  $currency  Read the amount as money (taka and paisa) rather than a plain number.
     * @param  string  $system  'bd' for lakh/crore or 'international' for million/billion (English only).
     */
    function numberToWords(mixed $value, ?string $locale = null, bool $currency = true, string $system = 'bd'): string
    {
        $locale ??= app()->getLocale();
        $value = preg_replace('/[^0-9.-]/', '', (string) $value);

        if ($value === '' || ! is_numeric($value)) {
            $value = '0';
        }

        if ($locale === 'bn') {
            return convertNumberToWordInBangla($value, $currency);
        }

        $negative = (float) $value < 0;
        $value = ltrim($value, '-');

        if ($currency) {
            [$wholeNumber, $decimalNumber] = explode('.', number_format((float) $value, 2, '.', ''));
            $majorUnit = mb_strtoupper(config('app.currency_name') ?? 'TAKA');
            $minorUnit = mb_strtoupper(config('app.currency_fraction_name') ?? 'PAISA');

            $parts = [];

            if ((int) $wholeNumber > 0 || (int) $decimalNumber === 0) {
                $parts[] = convertIntegerToWordInEnglish((int) $wholeNumber, $system).' '.$majorUnit;
            }

            if ((int) $decimalNumber > 0) {
                $parts[] = convertIntegerToWordInEnglish((int) $decimalNumber, $system).' '.$minorUnit;
            }

            $words = implode(' AND ', $parts).' ONLY';
        } else {
            [$wholeNumber, $decimalNumber] = array_pad(explode('.', $value, 2), 2, '');
            $words = convertIntegerToWordInEnglish((int) $wholeNumber, $system);

            if (rtrim($decimalNumber, '0') !== '') {
                $digits = array_map(fn (string $digit): string => convertIntegerToWordInEnglish((int) $digit), str_split($decimalNumber));
                $words .= ' POINT '.implode(' ', $digits);
            }
        }

        return $negative && (float) $value != 0 ? 'MINUS '.$words : $words;
    }
}

if (! function_exists('getBanglaNumbers')) {
    /**
     * Get the Bangla words for the numbers 0 to 100, keyed by number.
     *
     * @return array<int, string>
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

if (! function_exists('convertIntegerToWordInBangla')) {
    /**
     * Spell an integer in Bangla words using the crore/lakh/thousand/hundred system.
     *
     * @param  int  $number  The integer to spell.
     * @return string The words, e.g. 'এক শো পঞ্চাশ কোটি'.
     */
    function convertIntegerToWordInBangla(int $number): string
    {
        $ones = getBanglaNumbers();

        if ($number === 0) {
            return $ones[0];
        }

        if ($number < 0) {
            return 'ঋণাত্মক '.convertIntegerToWordInBangla(-$number);
        }

        $words = [];
        $crore = intdiv($number, 10000000);

        // Crore counts are unbounded ("এক শো পঞ্চাশ কোটি"), so spell them recursively
        if ($crore > 0) {
            $words[] = convertIntegerToWordInBangla($crore).' কোটি';
            $number %= 10000000;
        }

        $lakh = intdiv($number, 100000);
        $thousand = intdiv($number % 100000, 1000);
        $hundred = intdiv($number % 1000, 100);
        $lastTwoDigits = $number % 100;

        if ($lakh > 0) {
            $words[] = $ones[$lakh].' লাখ';
        }

        if ($thousand > 0) {
            $words[] = $ones[$thousand].' হাজার';
        }

        if ($hundred > 0) {
            $words[] = $ones[$hundred].' শো';
        }

        if ($lastTwoDigits > 0) {
            $words[] = $ones[$lastTwoDigits];
        }

        return implode(' ', $words);
    }
}

if (! function_exists('convertNumberToWordInBangla')) {
    /**
     * Convert a numeric value to words in Bangla, as a taka/poysha amount by default.
     *
     * In poysha mode the value is rounded to two decimals. Formatted strings such as '1,500.50' and floats are accepted.
     *
     * @param  string|int|float  $number  The amount, optionally with a decimal part.
     * @param  bool  $isPoysha  Read the decimal part as poysha instead of digit by digit.
     * @return string e.g. 'এক হাজার দুই শো পঞ্চাশ টাকা পঞ্চাশ পয়সা মাত্র'.
     */
    function convertNumberToWordInBangla(string|int|float $number, bool $isPoysha = true): string
    {
        $ones = getBanglaNumbers();
        $number = preg_replace('/[^0-9.-]/', '', (string) $number);

        if ($number === '' || ! is_numeric($number)) {
            $number = '0';
        }

        $negative = (float) $number < 0;
        $number = ltrim($number, '-');

        if ($isPoysha) {
            $number = number_format((float) $number, 2, '.', '');
        }

        [$integerPart, $decimalPart] = array_pad(explode('.', $number, 2), 2, '');
        $integerPart = (int) $integerPart;
        $words = [];

        if ($isPoysha) {
            $poysha = (int) $decimalPart;

            if ($integerPart > 0 || $poysha === 0) {
                $words[] = convertIntegerToWordInBangla($integerPart).' টাকা';
            }

            if ($poysha > 0) {
                $words[] = $ones[$poysha].' পয়সা';
            }
        } else {
            $words[] = convertIntegerToWordInBangla($integerPart);

            if ($decimalPart !== '') {
                $words[] = 'দশমিক';

                foreach (str_split($decimalPart) as $digit) {
                    $words[] = $ones[(int) $digit];
                }
            }
        }

        $words[] = 'মাত্র';

        $result = implode(' ', $words);

        return $negative && (float) $number != 0 ? 'ঋণাত্মক '.$result : $result;
    }
}

if (! function_exists('generateRandomFloat')) {
    /**
     * Generate a random float number within a specified range.
     *
     * @param  float  $min  Minimum value of the range (inclusive).
     * @param  float  $max  Maximum value of the range (inclusive).
     * @param  int  $decimals  Number of decimal places to round the result to (default is 2).
     * @return float A random float number between the specified minimum and maximum values.
     */
    function generateRandomFloat($min, $max, $decimals = 2)
    {
        $scale = pow(10, $decimals); // Determine the scale based on the desired decimal places.

        // Generate a random integer and scale it back to a float.
        return (float) (mt_rand((int) round($min * $scale), (int) round($max * $scale)) / $scale);
    }
}
