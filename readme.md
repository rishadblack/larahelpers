# LaraHelpers

**LaraHelpers** is a collection of useful helper functions for Laravel applications, designed to enhance productivity and streamline common tasks.

## Installation

You can install the package via Composer. Run the following command in your Laravel project directory:

```bash
composer require rishadblack/larahelpers
```

# Function Documentation

This repository contains a collection of utility functions for various tasks. Below is a list of all functions along with a brief description of their purpose.

# Function List

## `generateRandomFloat`

Generates a random floating-point number within a specified range.

**Parameters:**
- `$min` *(float)*: The minimum value of the range (inclusive).
- `$max` *(float)*: The maximum value of the range (inclusive).
- `$decimals` *(int)*: The number of decimal places (default is `2`).

**Returns:** A random float number between `$min` and `$max`.

**Example:**
```php
$randomNumber = generateRandomFloat(1.0, 10.0); // e.g., 5.23
```
### `matchRouteParameter`

Matches the route parameter with the provided data.

**Parameters:**

- `$Data` *(array)*: An array of data to match against.

**Returns:** `true` if the route parameter matches; `otherwise`, `false`.

**Example:**

```php

$result = matchRouteParameter(['id' => 123]); // true or false based on the current request
```
### `switchColLang`

Switches the column name based on the application's locale.

**Parameters:**

- `$enCol` *(string)*: The English column name.
- `$bnCol` *(string)*: The Bangla column name.

**Returns:** The column name based on the current locale.

**Example:**

```php

$columnName = switchColLang('name', 'নাম'); // 'name' or 'নাম' based on locale
```
### `perPageRows`

Returns an array of rows per page options.

**Parameters:**

- `$Data` *(array)*: An array of data options.

**Returns:** An array of row options or a default `array [10, 25, 50, 100, 250]`.

**Example:**

```php

$options = perPageRows(); // [10, 25, 50, 100, 250]
```
### `addAllField`

Adds an "All" option to the provided data.

**Parameters:**

- `$Data` *(Collection|array)*: A collection or array of data to which "All" is added.

**Returns:** An array with "`All`" prepended to the original data.

**Example:**

```php

$fields = addAllField($data); // [null => 'All', ...]
```
### `currencySymbol`

Returns the currency symbol used in the application.

**Returns:** A string representing the currency symbol.

**Example:**

```php

$symbol = currencySymbol(); // '৳'
```
### `numberEnToBn`

Converts English numerals to Bangla numerals.

**Parameters:**

- `$number` *(string)*: The number in English numerals.

**Returns:** A `string` with Bangla numerals.

**Example:**

```php

$banglaNumber = numberEnToBn('123'); // '১২৩'
```
### `asset_storage`

Generates the storage asset URL.

**Parameters:**

- `$path` *(string)*: The path to the asset.

**Returns:** A URL `string` for the asset.

**Example:**

```php

$url = asset_storage('uploads/file.jpg'); // URL for the asset
```
### `asset_favicon`

Generates the favicon asset URL.

**Parameters:**

- `$path` *(string|null)*: The path to the favicon (defaults to config('app.logo')).

**Returns:** A URL string for the favicon asset.

**Example:**

```php

$faviconUrl = asset_favicon(); // URL for the favicon
```
### `asset_logo`

Generates the logo asset URL.

**Parameters:**

- `$path` *(string|null)*: The path to the logo (defaults to config('app.logo')).

**Returns:** A URL string for the logo asset.

**Example:**

```php

$logoUrl = asset_logo(); // URL for the logo
```
### `asset_powered_logo`

Generates the powered logo asset URL.

**Parameters:**

- `$path` *(string|null)*: The path to the powered logo (defaults to config('app.logo_powered')).

**Returns:** A URL string for the powered logo asset.

**Example:**

```php

$poweredLogoUrl = asset_powered_logo(); // URL for the powered logo
```
### `asset_dark_logo`

Generates the dark logo asset URL.

**Parameters:**

- `$path` *(string|null)*: The path to the dark logo (defaults to config('app.dark_logo')).

**Returns:** A URL string for the dark logo asset.

**Example:**

```php

$darkLogoUrl = asset_dark_logo(); // URL for the dark logo
```
### `asset_profile_picture`

Generates the default profile picture asset URL.

**Returns:** A URL `string` for the profile picture asset.

**Example:**

```php

$profilePicUrl = asset_profile_picture(); // URL for the profile picture
```
### `numberFormatConverted`

Formats a number with optional currency sign and decimal places.

**Parameters:**

- `$value` *(float|string)*: The number to format.
- `$sign` *(string|bool)*: Optional currency sign (default is false).
- `$decimal` *(int)*: Number of decimal places (default is 2).
- `$thousend` *(string)*: Optional thousand separator.

**Returns:** A formatted `string`.

**Example:**

```php

$formatted = numberFormatConverted(12345.678, true); // '$12,345.68' or '৳12,345.68'
```
### `percentFormat`

Formats a number as a percentage.

**Parameters:**

- `$value` *(float|string)*: The number to format.
- `$decimal` *(int)*: Number of decimal places (default is 2).
- `$percentSign` *(string)*: The percent sign (default is %).

**Returns:** A formatted percentage `string`.

**Example:**

```php

$percentage = percentFormat(0.256); // '25.60%'
```
### `pointFormat`

Formats a number for display with optional currency sign.

**Parameters:**

- `$value` *(float|string)*: The number to format.
- `$sign` *(string|bool)*: Optional currency sign (default is false).
- `$decimal` *(int)*: Number of decimal places (default is 2).
- `$thousend` *(string)*: Optional thousand separator.

**Returns:** A formatted `string`.

**Example:**

```php

$formattedPoint = pointFormat(1234.567, true); // '৳1,234.57'
```
### `unitFormat`

Formats a number with a unit identifier.

**Parameters:**

- `$value` *(float|string)*: The number to format.
- `$unitId` *(string|bool)*: Optional unit identifier (default is false).
- `$decimal` *(int)*: Number of decimal places (default is 0).

**Returns:** A formatted `string` with unit.

**Example:**

```php

$formattedUnit = unitFormat(5000, 'kg'); // '5,000kg'
```
### `numberFormat`

Formats a number for display with optional currency sign.

**Parameters:**

- `$value` *(float|string)*: The number to format.
- `$sign` *(string|bool)*: Optional currency sign (default is false).
- `$decimal` *(int)*: Number of decimal places (default is 2).
- `$thousend` *(string)*: Optional thousand separator.

**Returns:** A formatted `string`.

**Example:**

```php

$formattedNumber = numberFormat(1000); // '1,000.00'
```
### `numberFormatOrPercent`

Formats a number or returns a percentage.

**Parameters:**

- `$value` *(float|string)*: The value to format.
- `$sign` *(bool)*: Whether to include a currency sign (default is false).
- `$decimal` *(bool)*: Whether to include decimals (default is false).
- `$thousend` *(string)*: Optional thousand separator.

**Returns:** A formatted `string` or percentage.

**Example:**

```php

$formattedValue = numberFormatOrPercent('20%'); // '20%'
```
### `getPercentOfValue`

Calculates the percentage of a given value.

**Parameters:**

- `$percentage` *(float|string)*: The percentage to calculate.
- `$amount` *(float|string)*: The amount to calculate the percentage of.
- `$percenSign` *(bool)*: Whether the percentage sign is included (default is true).

**Returns:** The calculated percentage of the amount.

**Example:**

```php

$percentValue = getPercentOfValue('20%', 100); // 20
```
### `getValueOfPercent`

Calculates the value based on a percentage of a given amount.

**Parameters:**

- `$percentage` *(float|string)*: The percentage value.
- `$amount` *(float|string)*: The amount to calculate the value from.

**Returns:** The calculated value based on the percentage.

**Example:**

```php

$value = getValueOfPercent('25%', 200); // 50
```
### `getTimeFormat`

Formats a given time into a specified format.

**Parameters:**

- `$time` *(string|DateTime)*: The time to format.
- `$format` *(string)*: The format to apply (default is 'Y-m-d H:i:s').

**Returns:** A formatted time `string`.

**Example:**

```php

$formattedTime = getTimeFormat('now'); // e.g., '2024-10-05 15:00:00'
```
### `getTimeFormatJs`

Formats a given time into a JavaScript-compatible format.

**Parameters:**

- `$time` *(string|DateTime)*: The time to format.

**Returns:** A JavaScript-compatible time `string`.

**Example:**

```php

$jsTime = getTimeFormatJs('now'); // e.g., '2024-10-05T15:00:00Z'
```
### `getfirstAndLastName`

Returns the first and last name from a full name string.

**Parameters:**

- `$fullName` *(string)*: The full name string.

**Returns:** An array containing the first and last names.

**Example:**

```php

$names = getfirstAndLastName('John Doe'); // ['John', 'Doe']
```
### `getFolderSize`

Calculates the size of a specified folder.

**Parameters:**

- `$dir` *(string)*: The path to the folder.

**Returns:** The size of the folder in bytes.

**Example:**

```php

$size = getFolderSize('/path/to/folder'); // e.g., 2048
```
### `getFormatSize`

Formats a size in bytes into a more human-readable format.

**Parameters:**

- `$bytes` *(int)*: The size in bytes.

**Returns:** A formatted string representing the size.

**Example:**

```php

$formattedSize = getFormatSize(2048); // '2 KB'
```
### `getCheckDevice`

Checks the user's device type.

**Returns:** A string representing the device type `(e.g., 'mobile', 'tablet', 'desktop')`.

**Example:**

```php

$device = getCheckDevice(); // 'desktop'
```
### `getGenerateDepth`

Generates the depth for nested structures.

**Parameters:**

- `$array` *(array)*: The array to calculate depth.

**Returns:** An integer representing the depth.

**Example:**

```php

$depth = getGenerateDepth(['a' => ['b' => ['c']]]); // 3
```
### `convertPipeToArray`

Converts a string with pipes into an array.

**Parameters:**

- `$string` *(string)*: The string to convert.

**Returns:** An array of values.

**Example:**

```php

`$array` = convertPipeToArray('one|two|three'); // ['one', 'two', 'three']
```
### `convertNumberToWordInEnglish`

Converts a number into words in English.

**Parameters:**

- `$number` *(int)*: The number to convert.

**Returns:** A string representing the number in words.

**Example:**

```php

$words = convertNumberToWordInEnglish(123); // 'one hundred twenty-three'
```
### `getBanglaNumbers`

Converts English numbers into Bangla numbers.

**Parameters:**

- `$number` *(int|string)*: The number to convert.

**Returns:** A string with Bangla numbers.

**Example:**

```php

$banglaNumbers = getBanglaNumbers(123); // '১২৩'
```
### `convertNumberToWordInBangla`

Converts a number into words in Bangla.

**Parameters:**
- `$number` *(int)*: The number to convert.

**Returns:** A string representing the number in Bangla words.

**Example:**

```php

$banglaWords = convertNumberToWordInBangla(123); // 'একশত তেইশ'
```
### `generateRandomFloat`

Generates a random floating-point number within a specified range.

**Parameters:**
- `$min` *(float)*: The minimum value of the range (inclusive).
- `$max` *(float)*: The maximum value of the range (inclusive).
- `$decimals` *(int)*: The number of decimal places (default is `2`).

**Returns:** A random float number between `$min` and `$max`.

**Example:**

```php
$randomNumber = generateRandomFloat(1.0, 10.0); // e.g., 5.23
```
## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request.

## License

This project is licensed under the MIT License. See the LICENSE file for more information.
