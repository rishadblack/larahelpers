# LaraHelpers

**LaraHelpers** is a collection of global helper functions for Laravel applications built for Bangladesh: number and currency formatting, lakh/crore abbreviations, amounts in words in English and Bangla, Bangla dates, fiscal years, Bangladeshi mobile numbers, branding asset URLs and a few request, string and file utilities.

All helpers live in one file, `src/helpers.php`, and are autoloaded by Composer. Each is wrapped in `function_exists()`, so an application function with the same name wins. They use the Laravel container (`config()`, `request()`, `app()`, `asset()`), so call them inside a booted Laravel app.

## Requirements

- PHP 8.3+
- Laravel 11, 12 or 13 (`illuminate/support`)

## Installation

```bash
composer require rishadblack/larahelpers
```

## Configuration

The package ships no config file. Add the keys you need to `config/app.php`:

```php
'currency_symbol' => '৳',                          // currencySymbol(), numberFormat($v, true)
'currency_name' => 'Taka',                         // numberToWords() major unit, default TAKA
'currency_fraction_name' => 'Paisa',               // numberToWords() minor unit, default PAISA
'point_sign' => 'pts',                             // pointFormat($v, true)
'fiscal_year_start_month' => 7,                    // fiscalYear(), default July
'logo' => 'images/logo.png',                       // asset_logo()
'dark_logo' => 'images/logo-dark.png',             // asset_dark_logo()
'logo_powered' => 'images/powered.png',            // asset_powered_logo()
'favicon' => 'favicon.ico',                        // asset_favicon()
'default_profile_picture' => 'images/avatar.png',  // asset_profile_picture()
```

## Number Formatting

Every formatter first strips all characters except digits, `.` and `-`, so `'1,234.50 ৳'`, `1234.5` and `null` are all accepted. An empty value is treated as `0`.

| Helper | Example | Result |
|---|---|---|
| `numberFormat($value, $sign = false, $decimal = false, $thousand = '')` | `numberFormat(1234.567)` | `1234.57` |
| | `numberFormat(1234.567, false, 1, ',')` | `1,234.6` |
| | `numberFormat(1234.5, false, true)` | `1235` (`true` means no decimals) |
| | `numberFormat(1234.5, true)` | `1,234.50 ৳` (`true` appends `currencySymbol()`, a string appends that string) |
| `numberFormatConverted($value, $sign = false, $decimal = false, $thousand = '')` | `numberFormatConverted('1,234.5', '$')` | `$1,234.50` (sign prepended) |
| `pointFormat($value, $sign = false, $decimal = false, $thousand = '')` | `pointFormat(1234.5, true)` | `1,234.50 pts` |
| `unitFormat($value, $unitId = false, $decimal = 0)` | `unitFormat(5000, 'kg')` | `5,000kg` |
| `percentFormat($value, $decimal = 2, $percentSign = '%')` | `percentFormat('25.456%', 1)` | `25.5%` (a `float` when `$percentSign` is `''`) |
| `numberFormatOrPercent($value, ...)` | `numberFormatOrPercent('20%')` | `20%` (values with `%` are returned as is) |
| `compactNumber($value, $decimals = 1, $system = 'bd', $locale = 'en')` | `compactNumber(15000000)` | `1.5 Cr` |
| | `compactNumber(1500000)` | `15 Lakh` |
| | `compactNumber(1500000, 1, 'international')` | `1.5M` |
| | `compactNumber(2500000, 1, 'bd', 'bn')` | `২৫ লাখ` |
| `currencySymbol()` | | `৳` or `config('app.currency_symbol')` |
| `numberEnToBn($number)` | `numberEnToBn('1,234.50')` | `১,২৩৪.৫০` |
| `numberBnToEn($number)` | `numberBnToEn('১,২৩৪.৫০')` | `1,234.50` |
| `getPercentOfValue($percentage, $amount, $percenSign = true)` | `getPercentOfValue('20%', 150)` | `30.0` |
| `getValueOfPercent($profit, $amount)` | `getValueOfPercent(150, 100)` | `50.0` (the margin `($profit - $amount) / $amount * 100`) |
| `generateRandomFloat($min, $max, $decimals = 2)` | `generateRandomFloat(1.5, 2.5)` | e.g. `2.13` |

Note that `numberFormat()`, `numberFormatConverted()` and `pointFormat()` use **no** thousand separator by default; pass `','`. When a sign is given, the number is always grouped with `,`.

## Numbers in Words

```php
numberToWords(1234.50);                              // en: 'ONE THOUSAND TWO HUNDRED THIRTY FOUR TAKA AND FIFTY PAISA ONLY'
numberToWords(1234.50, 'bn');                        // 'এক হাজার দুই শো চৌত্রিশ টাকা পঞ্চাশ পয়সা মাত্র'
numberToWords(1234567, 'en');                        // 'TWELVE LAKH THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN TAKA ONLY'
numberToWords(1234567, 'en', true, 'international'); // 'ONE MILLION TWO HUNDRED THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN TAKA ONLY'
numberToWords('1,234.50', 'en', false);              // 'ONE THOUSAND TWO HUNDRED THIRTY FOUR POINT FIVE ZERO'
numberToWords(0.5, 'en');                            // 'FIFTY PAISA ONLY'
numberToWords(-10, 'en');                            // 'MINUS TEN TAKA ONLY'
```

`numberToWords($value, $locale = null, $currency = true, $system = 'bd')` follows `app()->getLocale()` when `$locale` is null. The currency words come from `config('app.currency_name')` and `config('app.currency_fraction_name')`.

The lower level converters are available too:

```php
convertNumberToWordInEnglish(1234.50);          // 'ONE THOUSAND TWO HUNDRED THIRTY FOUR AND FIFTY TAKA ONLY'
convertNumberToWordInEnglish(1234.50, ' ONLY'); // custom suffix
convertNumberToWordInEnglish(-5);               // 'MINUS FIVE TAKA ONLY'

convertNumberToWordInBangla('1,250.50');        // 'এক হাজার দুই শো পঞ্চাশ টাকা পঞ্চাশ পয়সা মাত্র'
convertNumberToWordInBangla(1250);              // 'এক হাজার দুই শো পঞ্চাশ টাকা মাত্র'
convertNumberToWordInBangla(1500000000);        // 'এক শো পঞ্চাশ কোটি টাকা মাত্র'
convertNumberToWordInBangla('10.25', false);    // 'দশ দশমিক দুই পাঁচ মাত্র' (digits read one by one)

convertIntegerToWordInEnglish(1234567);         // 'ONE MILLION TWO HUNDRED THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN'
convertIntegerToWordInEnglish(1234567, 'bd');   // 'TWELVE LAKH THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN'
convertIntegerToWordInBangla(1234567);          // 'বারো লাখ চৌত্রিশ হাজার পাঁচ শো সাতষট্টি'
getBanglaNumbers()[45];                         // 'পঁয়তাল্লিশ' (0–100)
```

All converters accept formatted strings, floats and ints. Bangla amounts are rounded to two decimals in poysha mode; negatives are prefixed with `ঋণাত্মক`.

## Dates and Fiscal Years

```php
banglaDate('2026-10-07 15:05');             // '০৭-১০-২০২৬ ০৩:০৫ দুপুর' (default getTimeFormat())
banglaDate($order->created_at, 'd F Y, l'); // '০৭ অক্টোবর ২০২৬, বুধবার'
banglaDate(null, 'Y');                      // current year in Bangla digits

$year = fiscalYear();                       // today, July–June by default
$year['label'];                             // '2026-27'
$year['start'];                             // Carbon 2026-07-01 00:00:00
$year['end'];                               // Carbon 2027-06-30 23:59:59
$year['start_year'];                        // 2026
$year['end_year'];                          // 2027
fiscalYear('2026-03-31', 4)['label'];       // '2025-26' (April start)
fiscalYear('2026-03-31', 1)['label'];       // '2026' (calendar year)

getTimeFormat(7);                           // 'd/m/Y'; indexes 1–9, default 'd-m-Y h:i A'
getTimeFormatJs(7);                         // the same format for JS date pickers ('d-m-Y h:i K' by default)
```

`banglaDate()` accepts anything `Carbon::parse()` accepts and uses Carbon's `bn` locale for month, day and meridiem names. `fiscalYear()` reads `config('app.fiscal_year_start_month', 7)` when no start month is passed and throws `InvalidArgumentException` for a month outside 1–12.

## Phone Numbers

```php
formatBdPhone('+880 1712-345678');                  // '01712345678'
formatBdPhone('01712345678', 'international');      // '+8801712345678'
formatBdPhone('01712345678', 'dashed');             // '01712-345678'
formatBdPhone('0121234567');                        // null (not a valid mobile number)
isBdMobile('8801712345678');                        // true
```

`formatBdPhone()` accepts local, `+880`, `880`, `00880` and bare 10-digit forms with spaces or dashes anywhere, and returns `null` when the digits are not `01[3-9]` followed by eight more. Unknown formats throw `InvalidArgumentException`.

## Asset URLs

```php
asset_storage('uploads/a.jpg');  // asset('storage/uploads/a.jpg')
asset_logo();                    // asset(config('app.logo'))
asset_logo('custom/logo.png');   // the argument overrides the config value
asset_dark_logo(); asset_powered_logo(); asset_favicon(); asset_profile_picture();
```

`asset_storage()` assumes `php artisan storage:link` has been run.

## Request, Locale and UI Helpers

| Helper | Behaviour |
|---|---|
| `matchRouteParameter(['id' => 5])` | `true` when `request('id')` casts to the same int. Only the first array key is checked. |
| `switchColLang(['en' => 'name', 'bn' => 'name_bn'])` | Returns the entry for `app()->getLocale()`, falling back to `en`, then `''`. |
| `perPageRows()` / `perPageRows([5, 15])` | Page-size options; default `[10, 25, 50, 100, 250]`. |
| `addAllField($collectionOrArray)` | Prepends `'' => 'All'` (the key is an empty string). Accepts arrays and anything `Arrayable`. |
| `getCheckDevice()` | `1`, `2`, `3` for the exact user agents `app-android`, `app-ios`, `app-windows`; `null` otherwise. |

## String and File Helpers

| Helper | Behaviour |
|---|---|
| `getfirstAndLastName('John Ronald Doe', 'first')` | `'John'`; any other second argument gives the rest, `'Ronald Doe'`. A single word gives `''` for first and the word for last. |
| `initials('John Ronald Doe')` | `'JD'`; `initials('john ronald doe', 3)` gives `'JRD'`. Multibyte safe. |
| `maskString('01712345678')` | `'*******5678'`; `maskString($value, $visibleStart = 0, $visibleEnd = 4, $mask = '*')`. |
| `convertPipeToArray('a|b', '|')` | `['a', 'b']`; surrounding quotes are stripped; strings of 2 characters or fewer are returned unchanged. |
| `getGenerateDepth(3, '-')` | `'---'`, for indenting tree labels. |
| `getFolderSize($dir)` | Total bytes, recursive. |
| `getFormatSize($bytes)` | `'500 B'`, `'2 KB'` (rounded up), `'5 MB'`, `'3 GB'`, `'2 TB'`; negative input gives `'Invalid size'`. |

## Testing

The helpers need the container, so test them in feature tests. The package's own suite runs on Orchestra Testbench:

```bash
composer test
```

```php
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

it('formats money with the configured symbol', function () {
    config()->set('app.currency_symbol', '$');

    expect(numberFormat(1000, true))->toBe('1,000.00 $');
});

it('matches the request parameter', function () {
    app()->instance('request', Request::create('/', 'GET', ['id' => '7']));

    expect(matchRouteParameter(['id' => 7]))->toBeTrue();
});

it('resolves the current fiscal year', function () {
    Carbon::setTestNow('2026-03-15');

    expect(fiscalYear()['label'])->toBe('2025-26');
});
```

## Changelog

See [changelog.md](changelog.md).

## Contributing

See [contributing.md](contributing.md).

## License

MIT. See [license.md](license.md).
