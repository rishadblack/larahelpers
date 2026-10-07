---
name: larahelpers-development
description: "Use this skill when calling, changing or testing the global helper functions from rishadblack/larahelpers: number and currency formatting (numberFormat, pointFormat, unitFormat, percentFormat, numberFormatOrPercent, numberFormatConverted, compactNumber, currencySymbol, numberEnToBn, numberBnToEn), percentages (getPercentOfValue, getValueOfPercent), number to words in English or Bangla (numberToWords, convertNumberToWordInEnglish, convertNumberToWordInBangla, convertIntegerToWordInEnglish, convertIntegerToWordInBangla, getBanglaNumbers), Bangla dates and fiscal years (banglaDate, fiscalYear), Bangladeshi phone numbers (formatBdPhone, isBdMobile), branding and storage asset URLs (asset_storage, asset_logo, asset_dark_logo, asset_powered_logo, asset_favicon, asset_profile_picture), request and locale helpers (matchRouteParameter, switchColLang, getCheckDevice, perPageRows, addAllField, getTimeFormat, getTimeFormatJs), string and file helpers (getfirstAndLastName, initials, maskString, convertPipeToArray, getGenerateDepth, getFolderSize, getFormatSize) and generateRandomFloat. Also use it when adding a new helper to src/helpers.php or writing Pest tests for one."
license: MIT
metadata:
  author: rishadblack
---

# LaraHelpers Development

`rishadblack/larahelpers` is a single file, `src/helpers.php`, autoloaded by Composer (`autoload.files`). It declares global functions, each wrapped in `if (! function_exists(...))`. There is no service provider, config file or facade. The functions rely on the Laravel container (`config()`, `request()`, `app()`, `asset()`), so they only work inside a booted Laravel app.

## Conventions

- Add a new helper to `src/helpers.php` inside its own `function_exists()` guard, with a PHPDoc block, typed parameters where the existing callers allow it, and a Pest test in `tests/Feature`.
- Keep helpers pure and small. Read config through `config('app.*')`; do not add new config files to the package.
- Names are camelCase (`numberFormat`) except the asset helpers, which are snake_case (`asset_logo`). Follow the existing name in each group.
- Never declare a function with one of these names in the app. The guard means whichever file loads first wins, which is confusing to debug.

## Config Keys the Helpers Read

The package ships no config. Add the keys you use to `config/app.php`:

```php
'currency_symbol' => '৳',               // currencySymbol(), numberFormat($v, true)
'point_sign' => 'pts',                  // pointFormat($v, true)
'logo' => 'images/logo.png',            // asset_logo()
'dark_logo' => 'images/logo-dark.png',  // asset_dark_logo()
'logo_powered' => 'images/powered.png', // asset_powered_logo()
'favicon' => 'favicon.ico',             // asset_favicon()
'default_profile_picture' => 'images/avatar.png', // asset_profile_picture()
'currency_name' => 'Taka',              // numberToWords() major unit word (upper-cased), default TAKA
'currency_fraction_name' => 'Paisa',    // numberToWords() minor unit word (upper-cased), default PAISA
'fiscal_year_start_month' => 7,         // fiscalYear() first month, default 7 (July)
```

## Number Formatting

All of these first strip every character except digits, `.` and `-`, and treat an empty result as `0`. They accept ints, floats, numeric strings such as `'1,234.50'`, and `null`.

| Helper | Example | Result | Notes |
|---|---|---|---|
| `numberFormat($value, $sign = false, $decimal = false, $thousand = '')` | `numberFormat(1234.567)` | `1234.57` | **No thousand separator by default.** |
| | `numberFormat(1234.567, false, 1, ',')` | `1,234.6` | `$decimal` is the number of decimals. |
| | `numberFormat(1234.5, false, true)` | `1235` | `$decimal = true` means no decimals. |
| | `numberFormat(1234.5, true)` | `1,234.50 ৳` | `true` appends `currencySymbol()`. A string appends that string. Sign output always groups with `,` and ignores `$thousand`. |
| `numberFormatConverted($value, $sign = false, $decimal = false, $thousand = '')` | `numberFormatConverted('1,234.5', '$')` | `$1,234.50` | Like `numberFormat()` but the sign is **prepended** with no space. `$decimal` falsy means 2. Strips non-numeric characters like the others. |
| `compactNumber($value, $decimals = 1, $system = 'bd', $locale = 'en')` | `compactNumber(15000000)` | `1.5 Cr` | `bd` gives `1.5K`, `15 Lakh`, `1.5 Cr`; `international` gives `1.5K`, `1.5M`, `2.5B`, `3T`. `$locale = 'bn'` gives `১.৫ কোটি`, `২৫ লাখ`, `১.৫ হাজার`. Trailing zeros are dropped; below 1000 the number is returned as is. |
| `numberBnToEn($number)` | `numberBnToEn('১,২৩৪.৫০')` | `1,234.50` | Reverse of `numberEnToBn()`, for parsing input typed in Bangla digits. |
| `pointFormat($value, $sign = false, $decimal = false, $thousand = '')` | `pointFormat(1234.5, true)` | `1,234.50 pts` | `true` appends `config('app.point_sign')`. |
| `unitFormat($value, $unitId = false, $decimal = 0)` | `unitFormat(5000, 'kg')` | `5,000kg` | Always groups with `,`; the unit is appended with no space; `true` appends `unit`. |
| `percentFormat($value, $decimal = 2, $percentSign = '%')` | `percentFormat('25.456%', 1)` | `25.5%` | Returns a `float` when `$percentSign` is `''`. |
| `numberFormatOrPercent($value, ...)` | `numberFormatOrPercent('20%')` | `20%` | Values containing `%` are returned as they are; everything else goes through `numberFormat()`. |
| `currencySymbol()` | | `৳` | `config('app.currency_symbol')` or `৳`. |
| `numberEnToBn($number)` | `numberEnToBn('1,234.50')` | `১,২৩৪.৫০` | Digit replacement only; other characters are kept. |
| `getPercentOfValue($percentage, $amount, $percenSign = true)` | `getPercentOfValue('20%', 150)` | `30.0` | Pass `false` when `$percentage` is a plain number. |
| `getValueOfPercent($profit, $amount)` | `getValueOfPercent(150, 100)` | `50.0` | Despite the name this is the margin: `($profit - $amount) / $amount * 100`. Returns `0.0` when `$amount` is 0. |
| `generateRandomFloat($min, $max, $decimals = 2)` | `generateRandomFloat(1.5, 2.5)` | `2.13` | Always returns a `float`. |

## Number to Words

```php
convertNumberToWordInEnglish(1234.50);          // 'ONE THOUSAND TWO HUNDRED THIRTY FOUR AND FIFTY TAKA ONLY'
convertNumberToWordInEnglish(1234.50, ' ONLY'); // custom suffix; pass a string, not true
convertNumberToWordInEnglish(0);                // 'ZERO TAKA ONLY'

convertNumberToWordInBangla('1250.50');         // 'এক হাজার দুই শো পঞ্চাশ টাকা পঞ্চাশ পয়সা মাত্র'
convertNumberToWordInBangla(1250);              // 'এক হাজার দুই শো পঞ্চাশ টাকা মাত্র'
convertNumberToWordInBangla('10.25', false);    // 'দশ দশমিক দুই পাঁচ মাত্র' (digits read one by one)
getBanglaNumbers()[45];                         // 'পঁয়তাল্লিশ' (0–100)
```

- English output is upper case, uses short-scale groups up to quadrillion, and reads the first two decimal digits as a whole number (`.05` → `FIVE`, `.50` → `FIFTY`). Negative amounts start with `MINUS`.
- Bangla output uses crore/lakh/thousand/hundred (`কোটি`, `লাখ`, `হাজার`, `শো`); crore counts of any size are spelled (`এক শো পঞ্চাশ কোটি`). It always ends with `মাত্র`; with poysha on, the amount is rounded to two decimals and `টাকা` / `পয়সা` are inserted. Amounts below one taka read only the poysha (`পঞ্চাশ পয়সা মাত্র`), zero reads `শূন্য টাকা মাত্র`, and negatives start with `ঋণাত্মক`.
- Both accept formatted strings (`'1,500.50 ৳'`), floats and ints, and strip the separators.

Prefer `numberToWords()` for new code. It picks the language from `app()->getLocale()` (or the `$locale` argument), uses the lakh/crore system in English by default and places the currency words the way invoices expect:

```php
numberToWords(1234.50);                            // locale en: 'ONE THOUSAND TWO HUNDRED THIRTY FOUR TAKA AND FIFTY PAISA ONLY'
numberToWords(1234.50, 'bn');                      // 'এক হাজার দুই শো চৌত্রিশ টাকা পঞ্চাশ পয়সা মাত্র'
numberToWords(1234567, 'en');                      // 'TWELVE LAKH THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN TAKA ONLY'
numberToWords(1234567, 'en', true, 'international'); // 'ONE MILLION TWO HUNDRED ... TAKA ONLY'
numberToWords('1,234.50', 'en', false);            // plain number: 'ONE THOUSAND TWO HUNDRED THIRTY FOUR POINT FIVE ZERO'
numberToWords(0.5, 'en');                          // 'FIFTY PAISA ONLY'

convertIntegerToWordInEnglish(1500000000, 'bd');   // 'ONE HUNDRED FIFTY CRORE'
convertIntegerToWordInEnglish(2000005);            // 'TWO MILLION FIVE' (international is the default here)
convertIntegerToWordInBangla(1234567);             // 'বারো লাখ চৌত্রিশ হাজার পাঁচ শো সাতষট্টি'
```

The currency words come from `config('app.currency_name')` and `config('app.currency_fraction_name')` (upper-cased), defaulting to `TAKA` and `PAISA`.

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
| `switchColLang(['en' => 'name', 'bn' => 'name_bn'])` | Returns the entry for `app()->getLocale()`, falling back to `en`, then `''`. Use it to pick a column name per locale. |
| `perPageRows()` / `perPageRows([5, 15])` | Page-size options; default `[10, 25, 50, 100, 250]`. |
| `addAllField($collectionOrArray)` | Prepends `'' => 'All'` (the key is an empty string, not `null`). Accepts arrays and anything `Arrayable`. |
| `getCheckDevice()` | `1`, `2`, `3` for the exact user agents `app-android`, `app-ios`, `app-windows`; `null` for anything else, including browsers. |
| `getTimeFormat($index = null)` | A PHP date format by index 1–9 (`7` → `d/m/Y`), default `d-m-Y h:i A`. |
| `getTimeFormatJs($index = null)` | The same format rewritten for JS date pickers (`d-m-Y h:i K`; `getTimeFormatJs(7)` → `d/m/Y`). |

## String and File Helpers

| Helper | Behaviour |
|---|---|
| `getfirstAndLastName('John Ronald Doe', 'first')` | `'John'`; any other second argument gives the rest, `'Ronald Doe'`. A single-word name gives `''` for first and the word for last. Repeated whitespace is collapsed. |
| `initials('John Ronald Doe')` | `'JD'`: first and last word when the name has more words than `$limit` (default 2); `initials('john ronald doe', 3)` → `'JRD'`. Multibyte safe. |
| `maskString('01712345678')` | `'*******5678'`; `maskString($value, $visibleStart = 0, $visibleEnd = 4, $mask = '*')`. Strings too short to mask are returned unchanged. |
| `formatBdPhone('+880 1712-345678')` | `'01712345678'`. Accepts local, `+880`, `880`, `00880` and bare 10-digit forms with spaces or dashes; returns `null` when it is not a valid mobile number (`01[3-9]` + 8 digits). `$format` is `'local'`, `'international'` (`+8801712345678`) or `'dashed'` (`01712-345678`); anything else throws `InvalidArgumentException`. |
| `isBdMobile($phone)` | `true` when `formatBdPhone()` would return a number. Use it in validation closures. |
| `convertPipeToArray('a|b', '|')` | `['a', 'b']`; surrounding quotes are stripped first; strings of 2 characters or fewer are returned unchanged. |
| `getGenerateDepth(3, '-')` | `'---'`, for indenting tree labels. |
| `getFolderSize($dir)` | Total bytes, recursive. |
| `getFormatSize($bytes)` | `'500 B'`, `'2 KB'` (rounded **up**), `'5 MB'`, `'3 GB'`, `'2 TB'`; negative input gives `'Invalid size'`. |

## Date Helpers

```php
banglaDate('2026-10-07 15:05');            // '০৭-১০-২০২৬ ০৩:০৫ দুপুর' (default getTimeFormat())
banglaDate($order->created_at, 'd F Y, l'); // '০৭ অক্টোবর ২০২৬, বুধবার'
banglaDate(null, 'Y');                     // current year in Bangla digits

$year = fiscalYear();                      // today, July–June by default
$year['label'];                            // '2026-27'
$year['start'];                            // Carbon 2026-07-01 00:00:00
$year['end'];                              // Carbon 2027-06-30 23:59:59
$year['start_year'];                       // 2026
$year['end_year'];                         // 2027
fiscalYear('2026-03-31', 4)['label'];      // '2025-26' (April start)
fiscalYear('2026-03-31', 1)['label'];      // '2026' (calendar year)
```

- `banglaDate()` accepts anything `Carbon::parse()` accepts and uses Carbon's `bn` locale for month, day and meridiem words, then swaps the digits. Spellings are Carbon's (`জানুয়ারী`, `দুপুর`).
- `fiscalYear()` reads `config('app.fiscal_year_start_month', 7)` when `$startMonth` is null and throws `InvalidArgumentException` outside 1–12. Use `$year['start']` and `$year['end']` directly in `whereBetween()` report filters.

## Testing with Pest

The helpers need the container, so write feature tests (the package's own tests extend Orchestra Testbench):

```php
use Illuminate\Http\Request;

it('formats money with the configured symbol', function () {
    config()->set('app.currency_symbol', '$');

    expect(numberFormat(1000, true))->toBe('1,000.00 $');
});

it('matches the request parameter', function () {
    app()->instance('request', Request::create('/', 'GET', ['id' => '7']));

    expect(matchRouteParameter(['id' => 7]))->toBeTrue();
});

it('detects the mobile app', function () {
    app()->instance('request', Request::create('/', 'GET', server: ['HTTP_USER_AGENT' => 'app-ios']));

    expect(getCheckDevice())->toBe(2);
});
```

- Asset helpers return `http://localhost/...` under Testbench; assert on the path.
- Use `app()->setLocale('bn')` for `switchColLang()`.
- For `generateRandomFloat()`, assert the range and `round($value, $decimals) === $value` rather than a fixed value.

## Common Pitfalls

- **Missing thousand separators:** `numberFormat()` and `pointFormat()` default to `$thousand = ''`. Pass `','`.
- **`$decimal = true` is not "two decimals":** it means zero decimals. Pass an int for a specific count.
- **`percentFormat()` return type changes:** it is a `string` with a sign and a `float` without one.
- **`addAllField()` key:** the "All" option has key `''`; a `wire:model` select bound to `null` will not preselect it unless the property is `''`.
- **`getValueOfPercent()` is a margin**, not "value of a percent". Use `getPercentOfValue()` for `20% of 150`.
- **Config keys are undefined** until you add them to `config/app.php`; `asset_logo()` with no config returns the app URL itself.
- **`getCheckDevice()` compares the whole user agent string**, so the mobile apps must send exactly `app-android`, `app-ios` or `app-windows`.
- **`numberToWords()` follows the app locale** when `$locale` is null, so the same call renders Bangla under `app()->setLocale('bn')`. Pass `'en'` explicitly for documents that must stay English.
- **`convertNumberToWordInEnglish()` and `numberToWords()` place the words differently:** the old helper gives `... AND FIFTY TAKA ONLY`, the new one gives `... TAKA AND FIFTY PAISA ONLY`. Do not mix them on one document.
- **`compactNumber()` defaults to lakh/crore.** Pass `'international'` for `M` / `B` output.
- **`formatBdPhone()` returns `null`** for invalid numbers rather than the input; check for null before saving.
- **Freeze time in date tests** with `Carbon::setTestNow('2026-10-07')` (and reset it in `afterEach`) before asserting on `banglaDate()` or `fiscalYear()` without a date.
