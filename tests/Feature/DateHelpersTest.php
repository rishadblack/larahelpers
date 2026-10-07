<?php

use Illuminate\Support\Carbon;

afterEach(fn () => Carbon::setTestNow());

it('formats dates with bangla names and digits', function () {
    expect(banglaDate('2026-10-07 15:05:00'))->toBe('০৭-১০-২০২৬ ০৩:০৫ দুপুর')
        ->and(banglaDate('2026-10-07', 'd F Y, l'))->toBe('০৭ অক্টোবর ২০২৬, বুধবার')
        ->and(banglaDate(Carbon::parse('2026-01-01 09:30'), getTimeFormat(1)))
        ->toBe(Carbon::parse('2026-01-01')->locale('bn')->translatedFormat('F').' ১, ২০২৬')
        ->and(banglaDate('2026-10-07 09:05', 'h:i A'))->toBe('০৯:০৫ সকাল');
});

it('formats the current date in bangla when no date is given', function () {
    Carbon::setTestNow('2026-10-07 15:05:00');

    expect(banglaDate())->toBe('০৭-১০-২০২৬ ০৩:০৫ দুপুর')
        ->and(banglaDate(null, 'Y'))->toBe('২০২৬');
});

it('resolves the july to june fiscal year by default', function () {
    $october = fiscalYear('2026-10-07');
    $march = fiscalYear('2026-03-01');

    expect($october['label'])->toBe('2026-27')
        ->and($october['start']->toDateTimeString())->toBe('2026-07-01 00:00:00')
        ->and($october['end']->toDateTimeString())->toBe('2027-06-30 23:59:59')
        ->and($october['start_year'])->toBe(2026)
        ->and($october['end_year'])->toBe(2027)
        ->and($march['label'])->toBe('2025-26')
        ->and($march['start']->toDateString())->toBe('2025-07-01')
        ->and($march['end']->toDateString())->toBe('2026-06-30')
        ->and(fiscalYear('2026-07-01')['label'])->toBe('2026-27')
        ->and(fiscalYear('2026-06-30')['label'])->toBe('2025-26');
});

it('uses today when no date is given and the configured start month', function () {
    Carbon::setTestNow('2026-03-15');

    expect(fiscalYear()['label'])->toBe('2025-26');

    config()->set('app.fiscal_year_start_month', 1);

    expect(fiscalYear()['label'])->toBe('2026')
        ->and(fiscalYear()['end_year'])->toBe(2026)
        ->and(fiscalYear()['end']->toDateString())->toBe('2026-12-31');
});

it('accepts a custom start month and rejects invalid ones', function () {
    $year = fiscalYear('2026-03-31', 4);

    expect($year['label'])->toBe('2025-26')
        ->and($year['start']->toDateString())->toBe('2025-04-01')
        ->and($year['end']->toDateString())->toBe('2026-03-31')
        ->and(fiscalYear('2026-04-01', 4)['label'])->toBe('2026-27')
        ->and(fn () => fiscalYear('2026-04-01', 13))->toThrow(InvalidArgumentException::class)
        ->and(fn () => fiscalYear('2026-04-01', 0))->toThrow(InvalidArgumentException::class);
});
