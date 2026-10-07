<?php

it('converts whole numbers to english words', function (int|string $value, string $words) {
    expect(convertNumberToWordInEnglish($value, ' ONLY'))->toBe($words);
})->with([
    'zero' => [0, 'ZERO ONLY'],
    'teens' => [15, 'FIFTEEN ONLY'],
    'tens' => [40, 'FORTY ONLY'],
    'hundreds' => [123, 'ONE HUNDRED TWENTY THREE ONLY'],
    'thousands' => [1234, 'ONE THOUSAND TWO HUNDRED THIRTY FOUR ONLY'],
    'millions' => [2000005, 'TWO MILLION FIVE ONLY'],
    'formatted string' => ['1,500', 'ONE THOUSAND FIVE HUNDRED ONLY'],
]);

it('converts decimals to english words', function () {
    expect(convertNumberToWordInEnglish(10.05, ' ONLY'))->toBe('TEN AND FIVE ONLY')
        ->and(convertNumberToWordInEnglish(10.15, ' ONLY'))->toBe('TEN AND FIFTEEN ONLY')
        ->and(convertNumberToWordInEnglish(10.50, ' ONLY'))->toBe('TEN AND FIFTY ONLY')
        ->and(convertNumberToWordInEnglish(10.75, ' ONLY'))->toBe('TEN AND SEVENTY FIVE ONLY');
});

it('appends taka only by default', function () {
    expect(convertNumberToWordInEnglish(5))->toBe('FIVE TAKA ONLY');
});

it('exposes the bangla number words', function () {
    expect(getBanglaNumbers())->toHaveCount(101)
        ->and(getBanglaNumbers()[0])->toBe('শূন্য')
        ->and(getBanglaNumbers()[99])->toBe('নিরানব্বই');
});

it('converts numbers to bangla words', function (int|string $value, string $words) {
    expect(convertNumberToWordInBangla($value))->toBe($words);
})->with([
    'tens' => [45, 'পঁয়তাল্লিশ টাকা মাত্র'],
    'hundreds' => [123, 'এক শো তেইশ টাকা মাত্র'],
    'lakhs' => [1234567, 'বারো লাখ চৌত্রিশ হাজার পাঁচ শো সাতষট্টি টাকা মাত্র'],
    'crores' => [20000000, 'দুই কোটি টাকা মাত্র'],
    'poysha' => ['10.50', 'দশ টাকা পঞ্চাশ পয়সা মাত্র'],
    'one decimal digit' => ['10.5', 'দশ টাকা পঞ্চাশ পয়সা মাত্র'],
]);

it('converts decimals digit by digit when poysha is off', function () {
    expect(convertNumberToWordInBangla('10.25', false))->toBe('দশ দশমিক দুই পাঁচ মাত্র');
});

it('spells negative numbers and large scales in english', function () {
    expect(convertNumberToWordInEnglish(-5))->toBe('MINUS FIVE TAKA ONLY')
        ->and(convertNumberToWordInEnglish(-1234.5, ' ONLY'))->toBe('MINUS ONE THOUSAND TWO HUNDRED THIRTY FOUR AND FIFTY ONLY')
        ->and(convertNumberToWordInEnglish(1000000000000000, ' ONLY'))->toBe('ONE QUADRILLION ONLY')
        ->and(convertNumberToWordInEnglish('abc'))->toBe('ZERO TAKA ONLY');
});

it('spells integers in english using either numbering system', function () {
    expect(convertIntegerToWordInEnglish(0))->toBe('ZERO')
        ->and(convertIntegerToWordInEnglish(-42))->toBe('MINUS FORTY TWO')
        ->and(convertIntegerToWordInEnglish(1234567))->toBe('ONE MILLION TWO HUNDRED THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN')
        ->and(convertIntegerToWordInEnglish(1234567, 'bd'))->toBe('TWELVE LAKH THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN')
        ->and(convertIntegerToWordInEnglish(1500000000, 'bd'))->toBe('ONE HUNDRED FIFTY CRORE')
        ->and(convertIntegerToWordInEnglish(20000000000000, 'bd'))->toBe('TWENTY LAKH CRORE')
        ->and(convertIntegerToWordInEnglish(100005, 'bd'))->toBe('ONE LAKH FIVE');
});

it('accepts formatted strings and floats in bangla', function () {
    expect(convertNumberToWordInBangla('1,500'))->toBe('এক হাজার পাঁচ শো টাকা মাত্র')
        ->and(convertNumberToWordInBangla(1250.5))->toBe('এক হাজার দুই শো পঞ্চাশ টাকা পঞ্চাশ পয়সা মাত্র')
        ->and(convertNumberToWordInBangla('1,250.50 ৳'))->toBe('এক হাজার দুই শো পঞ্চাশ টাকা পঞ্চাশ পয়সা মাত্র');
});

it('handles crore above ninety nine, rounding, zero and negatives in bangla', function () {
    expect(convertNumberToWordInBangla(1500000000))->toBe('এক শো পঞ্চাশ কোটি টাকা মাত্র')
        ->and(convertNumberToWordInBangla('1.505'))->toBe('এক টাকা একান্ন পয়সা মাত্র')
        ->and(convertNumberToWordInBangla(0.5))->toBe('পঞ্চাশ পয়সা মাত্র')
        ->and(convertNumberToWordInBangla(0))->toBe('শূন্য টাকা মাত্র')
        ->and(convertNumberToWordInBangla('abc'))->toBe('শূন্য টাকা মাত্র')
        ->and(convertNumberToWordInBangla(-10))->toBe('ঋণাত্মক দশ টাকা মাত্র')
        ->and(convertNumberToWordInBangla('0.75', false))->toBe('শূন্য দশমিক সাত পাঁচ মাত্র');
});

it('spells integers in bangla', function () {
    expect(convertIntegerToWordInBangla(0))->toBe('শূন্য')
        ->and(convertIntegerToWordInBangla(1234567))->toBe('বারো লাখ চৌত্রিশ হাজার পাঁচ শো সাতষট্টি')
        ->and(convertIntegerToWordInBangla(1500000000))->toBe('এক শো পঞ্চাশ কোটি')
        ->and(convertIntegerToWordInBangla(-7))->toBe('ঋণাত্মক সাত');
});

it('spells money in english with taka and paisa using the lakh crore system', function () {
    expect(numberToWords(1234.5, 'en'))->toBe('ONE THOUSAND TWO HUNDRED THIRTY FOUR TAKA AND FIFTY PAISA ONLY')
        ->and(numberToWords('12,34,567', 'en'))->toBe('TWELVE LAKH THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN TAKA ONLY')
        ->and(numberToWords(1234567, 'en', true, 'international'))->toBe('ONE MILLION TWO HUNDRED THIRTY FOUR THOUSAND FIVE HUNDRED SIXTY SEVEN TAKA ONLY')
        ->and(numberToWords(0.5, 'en'))->toBe('FIFTY PAISA ONLY')
        ->and(numberToWords(0, 'en'))->toBe('ZERO TAKA ONLY')
        ->and(numberToWords(-1234.5, 'en'))->toBe('MINUS ONE THOUSAND TWO HUNDRED THIRTY FOUR TAKA AND FIFTY PAISA ONLY');
});

it('spells plain numbers in english reading decimals digit by digit', function () {
    expect(numberToWords('1,234.50', 'en', false))->toBe('ONE THOUSAND TWO HUNDRED THIRTY FOUR POINT FIVE ZERO')
        ->and(numberToWords(12, 'en', false))->toBe('TWELVE')
        ->and(numberToWords('12.00', 'en', false))->toBe('TWELVE')
        ->and(numberToWords(-0.5, 'en', false))->toBe('MINUS ZERO POINT FIVE');
});

it('uses the configured currency names', function () {
    config()->set('app.currency_name', 'Dollar');
    config()->set('app.currency_fraction_name', 'Cent');

    expect(numberToWords(10.25, 'en'))->toBe('TEN DOLLAR AND TWENTY FIVE CENT ONLY');
});

it('spells numbers in the application locale by default', function () {
    app()->setLocale('bn');

    expect(numberToWords(1234.5))->toBe('এক হাজার দুই শো চৌত্রিশ টাকা পঞ্চাশ পয়সা মাত্র')
        ->and(numberToWords('10.25', null, false))->toBe('দশ দশমিক দুই পাঁচ মাত্র');

    app()->setLocale('en');

    expect(numberToWords(5))->toBe('FIVE TAKA ONLY');
});
