# Changelog

All notable changes to `larahelpers` will be documented in this file.

## 2.0.1 - 2026-10-08

### Changed

- Dependencies refreshed and tested on PHP 8.4 (Symfony 8.1 components). No helper code changes.
- Added a GitHub Actions workflow that runs the suite on PHP 8.3 and 8.4 against Laravel 11, 12 and 13.
- The Composer description now matches what the package does.

## 2.0.0 - 2026-10-07

### Changed

- Behaviour changes that may affect existing output: negative amounts are now spelled with `MINUS` (English) or `ঋণাত্মক` (Bangla) instead of being dropped, Bangla poysha amounts are rounded to two decimals, and `numberFormatConverted()` now strips non-numeric characters before formatting.

### Added

- `numberToWords()` spells an amount in the current or given locale. English output uses the lakh/crore system by default and reads decimals as `TAKA AND ... PAISA ONLY`; the currency words come from `config('app.currency_name')` and `config('app.currency_fraction_name')`.
- `convertIntegerToWordInEnglish()` and `convertIntegerToWordInBangla()` spell integers, with an `international` or `bd` numbering system in English.
- `compactNumber()` abbreviates numbers as `1.5K`, `15 Lakh`, `1.5 Cr` (or `K`, `M`, `B`, `T`), optionally with Bangla digits and unit words.
- `numberBnToEn()` converts Bangla digits back to English digits.
- `banglaDate()` formats a date with Bangla month and day names, meridiem and digits.
- `fiscalYear()` resolves the July to June fiscal year (configurable with `config('app.fiscal_year_start_month')`) with label, start, end and years.
- `formatBdPhone()` and `isBdMobile()` normalise and validate Bangladeshi mobile numbers in local, international and dashed styles.
- `maskString()` masks the middle of a string, keeping a number of characters visible at each end.
- `initials()` builds upper case initials from a name.
- `getTimeFormatJs()` now accepts the same format index as `getTimeFormat()`.

### Fixed

- `convertNumberToWordInBangla()` now strips thousand separators (`'1,500'` was read as `1`), accepts floats without dropping the poysha, spells crore counts above 99 (`150 crore` produced an empty word) and rounds amounts to two decimals instead of losing the taka word for `1.505`. Zero amounts read `শূন্য টাকা মাত্র`, amounts below one taka read only the poysha, and negatives are prefixed with `ঋণাত্মক`.
- `convertNumberToWordInEnglish()` spells negative amounts with `MINUS` instead of returning `ZERO`, and spells `QUADRILLION` correctly.
- `numberFormatConverted()` strips non-numeric characters like the other formatters, so `'1,234.5'` formats as `1234.50` instead of `1.00`.
- `getfirstAndLastName()` collapses repeated whitespace, so `'John  Doe'` gives `'Doe'` instead of `' Doe'`.

## 1.0

### Added

- Everything
