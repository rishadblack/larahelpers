<?php

it('formats numbers with the default two decimals and no thousand separator', function () {
    expect(numberFormat(1000))->toBe('1000.00')
        ->and(numberFormat('1,234.567'))->toBe('1234.57')
        ->and(numberFormat('abc'))->toBe('0.00')
        ->and(numberFormat(null))->toBe('0.00');
});

it('formats numbers with a thousand separator and custom decimals', function () {
    expect(numberFormat(1234567.891, false, 1, ','))->toBe('1,234,567.9')
        ->and(numberFormat(1234.4, false, true))->toBe('1234')
        ->and(numberFormat(1234.5, false, 0))->toBe('1235');
});

it('appends the currency symbol when a sign is requested', function () {
    config()->set('app.currency_symbol', '$');

    expect(numberFormat(1000, true))->toBe('1,000.00 $')
        ->and(numberFormat(1000, '€'))->toBe('1,000.00 €');
});

it('returns the configured or default currency symbol', function () {
    expect(currencySymbol())->toBe('৳');

    config()->set('app.currency_symbol', '$');

    expect(currencySymbol())->toBe('$');
});

it('keeps percentages as they are and formats everything else', function () {
    expect(numberFormatOrPercent('20%'))->toBe('20%')
        ->and(numberFormatOrPercent('1,500'))->toBe('1500.00')
        ->and(numberFormatOrPercent(20, false, true))->toBe('20');
});

it('formats a converted number with an optional sign', function () {
    expect(numberFormatConverted(12345.678))->toBe('12345.68')
        ->and(numberFormatConverted(12345.678, '$'))->toBe('$12,345.68')
        ->and(numberFormatConverted(12345.678, false, 1, ','))->toBe('12,345.7')
        ->and(numberFormatConverted('1,234.5'))->toBe('1234.50')
        ->and(numberFormatConverted('৳ 1,234.5', '৳'))->toBe('৳1,234.50')
        ->and(numberFormatConverted(null))->toBe('0.00');
});

it('converts bangla digits to english digits', function () {
    expect(numberBnToEn('১,২৩৪.৫০ টাকা'))->toBe('1,234.50 টাকা')
        ->and(numberBnToEn('২০২৬'))->toBe('2026')
        ->and(numberBnToEn(numberEnToBn('9876543210')))->toBe('9876543210');
});

it('abbreviates numbers with lakh and crore by default', function () {
    expect(compactNumber(999))->toBe('999')
        ->and(compactNumber(1500))->toBe('1.5K')
        ->and(compactNumber(1500000))->toBe('15 Lakh')
        ->and(compactNumber(15000000))->toBe('1.5 Cr')
        ->and(compactNumber(125000000, 2))->toBe('12.5 Cr')
        ->and(compactNumber('1,23,45,678', 2))->toBe('1.23 Cr')
        ->and(compactNumber(-2500000))->toBe('-25 Lakh');
});

it('abbreviates numbers in the international system and in bangla', function () {
    expect(compactNumber(1500000, 1, 'international'))->toBe('1.5M')
        ->and(compactNumber(2500000000, 1, 'international'))->toBe('2.5B')
        ->and(compactNumber(3000000000000, 0, 'international'))->toBe('3T')
        ->and(compactNumber(2500000, 1, 'bd', 'bn'))->toBe('২৫ লাখ')
        ->and(compactNumber(15000000, 1, 'bd', 'bn'))->toBe('১.৫ কোটি')
        ->and(compactNumber(1500, 1, 'bd', 'bn'))->toBe('১.৫ হাজার');
});

it('formats percentages', function () {
    expect(percentFormat(25.456))->toBe('25.46%')
        ->and(percentFormat('25.456%', 1))->toBe('25.5%')
        ->and(percentFormat('12 percent', 0))->toBe('12%')
        ->and(percentFormat(25.456, 2, ''))->toBe(25.46)
        ->and(percentFormat('abc'))->toBe('0%');
});

it('formats points with the configured point sign', function () {
    config()->set('app.point_sign', 'pts');

    expect(pointFormat(1234.567))->toBe('1234.57')
        ->and(pointFormat(1234.567, true))->toBe('1,234.57 pts')
        ->and(pointFormat(1234.567, 'P'))->toBe('1,234.57 P')
        ->and(pointFormat('n/a'))->toBe('0.00');
});

it('formats units', function () {
    expect(unitFormat(5000, 'kg'))->toBe('5,000kg')
        ->and(unitFormat(5000.4, true))->toBe('5,000unit')
        ->and(unitFormat('1,250.5 pcs', false, 1))->toBe('1,250.5')
        ->and(unitFormat(''))->toBe('0');
});

it('calculates the percent of a value', function () {
    expect(getPercentOfValue('20%', 150))->toBe(30.0)
        ->and(getPercentOfValue(20, 150, false))->toBe(30.0);
});

it('calculates the profit margin percentage', function () {
    expect(getValueOfPercent(150, 100))->toBe(50.0)
        ->and(getValueOfPercent(100, 100))->toBe(0.0)
        ->and(getValueOfPercent(50, 100))->toBe(-50.0)
        ->and(getValueOfPercent(50, 0))->toBe(0.0);
});

it('converts english digits to bangla digits', function () {
    expect(numberEnToBn('1,234.50'))->toBe('১,২৩৪.৫০')
        ->and(numberEnToBn(2024))->toBe('২০২৪');
});

it('generates a random float inside the range with the requested decimals', function () {
    foreach (range(1, 50) as $attempt) {
        $value = generateRandomFloat(1.25, 2.75, 2);

        expect($value)->toBeFloat()->toBeGreaterThanOrEqual(1.25)->toBeLessThanOrEqual(2.75)
            ->and(round($value, 2))->toBe($value);
    }

    expect(generateRandomFloat(5, 5, 0))->toBe(5.0);
});
