# LaraHelpers

**LaraHelpers** is a collection of useful helper functions for Laravel applications, designed to enhance productivity and streamline common tasks.

## Installation

You can install the package via Composer. Run the following command in your Laravel project directory:

```bash
composer require rishadblack/larahelpers
```

# Function Documentation

This repository contains a collection of utility functions for various tasks. Below is a list of all functions along with a brief description of their purpose.

## Functions

### 1. `matchRouteParameter($Data = [])`

Checks if the current request parameter matches the provided data.

### 2. `switchColLang(array $columns)`

Switches the column name based on the application's current locale. Accepts an associative array of language codes and their corresponding column names.

### 3. `perPageRows($Data = [])`

Returns an array of items per page options. Defaults to `[10, 25, 50, 100, 250]` if no data is provided.

### 4. `addAllField($Data)`

Adds an "All" option to a given array of data. Returns an array that includes `null => 'All'` along with the provided data.

### 5. `currencySymbol()`

Returns the currency symbol for the application.

### 6. `numberEnToBn($number)`

Converts English numerals to Bangla numerals.

### 7. `asset_storage($path)`

Generates a URL for a given asset path in the storage directory.

### 8. `asset_favicon($path = null)`

Generates a URL for the application's favicon, falling back to the default logo if no path is provided.

### 9. `asset_logo($path = null)`

Generates a URL for the application's logo, using the default logo if no path is provided.

### 10. `asset_powered_logo($path = null)`

Generates a URL for the powered logo, falling back to the default if no path is specified.

### 11. `asset_dark_logo($path = null)`

Generates a URL for the dark version of the application logo.

### 12. `asset_profile_picture()`

Generates a URL for the default user profile picture.

### 13. `numberFormatConverted($value, $sign = false, $decimal = false, $thousend = '')`

Formats a number according to the specified decimal and sign, returning it as a string.

### 14. `percentFormat($value, $decimal = 2, $percentSign = '%')`

Formats a value as a percentage, rounding it to the specified number of decimal places.

### 15. `pointFormat($value, $sign = false, $decimal = false, $thousend = '')`

Formats a point value according to the specified sign and decimal places.

### 16. `unitFormat($value, $unitId = false, $decimal = 0)`

Formats a unit value according to the specified unit ID and decimal places.

### 17. `numberFormat($value, $sign = false, $decimal = false, $thousend = '')`

Formats a number with optional currency sign and specified decimal places.

### 18. `numberFormatOrPercent($value, $sign = false, $decimal = false, $thousend = '')`

Formats a number or percentage based on the input value.

### 19. `getPercentOfValue($value, $percent)`

Calculates the percentage of a given value.

### 20. `getValueOfPercent($value, $percent)`

Calculates the original value from a given percentage.

### 21. `getTimeFormat($time)`

Formats a time value into a readable string format.

### 22. `getTimeFormatJs($time)`

Formats a time value for JavaScript compatibility.

### 23. `getfirstAndLastName($fullName)`

Extracts the first and last names from a full name string.

### 24. `getFolderSize($path)`

Calculates the total size of a folder located at the specified path.

### 25. `getFormatSize($bytes)`

Formats a byte value into a more readable size string (e.g., KB, MB).

### 26. `getCheckDevice()`

Checks the type of device being used (e.g., mobile, desktop).

### 27. `getGenerateDepth($array)`

Generates the depth of a given nested array.

### 28. `convertPipeToArray($string)`

Converts a string with pipe delimiters into an array.

### 29. `convertNumberToWordInEnglish($number)`

Converts a number into its English word representation.

### 30. `getBanglaNumbers($number)`

Gets the Bangla representation of a number.

### 31. `convertNumberToWordInBangla($number)`

Converts a number into its Bangla word representation.

## Contributing

If you would like to contribute to this project, please fork the repository and submit a pull request.

## License

This project is licensed under the MIT License. See the LICENSE file for more information.
