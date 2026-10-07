<?php

use Illuminate\Http\Request;

it('matches a route parameter against the current request', function () {
    app()->instance('request', Request::create('/', 'GET', ['id' => '7']));

    expect(matchRouteParameter(['id' => 7]))->toBeTrue()
        ->and(matchRouteParameter(['id' => 8]))->toBeFalse()
        ->and(matchRouteParameter(['other' => 7]))->toBeFalse()
        ->and(matchRouteParameter())->toBeFalse();
});

it('switches a column name by locale with an english fallback', function () {
    $columns = ['en' => 'name', 'bn' => 'name_bn'];

    app()->setLocale('bn');
    expect(switchColLang($columns))->toBe('name_bn');

    app()->setLocale('fr');
    expect(switchColLang($columns))->toBe('name')
        ->and(switchColLang(['bn' => 'name_bn']))->toBe('');
});

it('returns per page options', function () {
    expect(perPageRows())->toBe([10, 25, 50, 100, 250])
        ->and(perPageRows([5, 15]))->toBe([5, 15]);
});

it('prepends an all option to collections and arrays', function () {
    expect(addAllField(collect([1 => 'Dhaka', 2 => 'Khulna'])))->toBe(['' => 'All', 1 => 'Dhaka', 2 => 'Khulna'])
        ->and(addAllField([1 => 'Dhaka']))->toBe(['' => 'All', 1 => 'Dhaka'])
        ->and(addAllField(collect()))->toBe(['' => 'All'])
        ->and(addAllField([]))->toBe(['' => 'All']);
});

it('returns date formats by index', function () {
    expect(getTimeFormat(1))->toBe('F j, Y')
        ->and(getTimeFormat(7))->toBe('d/m/Y')
        ->and(getTimeFormat(9))->toBe('d-m-y')
        ->and(getTimeFormat())->toBe('d-m-Y h:i A')
        ->and(getTimeFormat(99))->toBe('d-m-Y h:i A');
});

it('converts the default date format for javascript', function () {
    expect(getTimeFormatJs())->toBe('d-m-Y h:i K')
        ->and(getTimeFormatJs(7))->toBe('d/m/Y')
        ->and(getTimeFormatJs(1))->toBe('F j, Y');
});

it('splits first and last names', function () {
    expect(getfirstAndLastName('John Ronald Doe', 'first'))->toBe('John')
        ->and(getfirstAndLastName('John Ronald Doe', 'last'))->toBe('Ronald Doe')
        ->and(getfirstAndLastName('Cher', 'first'))->toBe('')
        ->and(getfirstAndLastName('Cher', 'last'))->toBe('Cher')
        ->and(getfirstAndLastName('  John   Doe ', 'first'))->toBe('John')
        ->and(getfirstAndLastName('  John   Doe ', 'last'))->toBe('Doe');
});

it('builds initials from a name', function () {
    expect(initials('John Ronald Doe'))->toBe('JD')
        ->and(initials('john ronald doe', 3))->toBe('JRD')
        ->and(initials('John Ronald Doe', 1))->toBe('J')
        ->and(initials('  cher '))->toBe('C')
        ->and(initials('রহিম উদ্দিন'))->toBe('রউ')
        ->and(initials(''))->toBe('')
        ->and(initials(null))->toBe('');
});

it('masks the middle of a string', function () {
    expect(maskString('01712345678'))->toBe('*******5678')
        ->and(maskString('01712345678', 2, 2, '#'))->toBe('01#######78')
        ->and(maskString('1234567890', 0, 0))->toBe('**********')
        ->and(maskString('abc', 2, 2))->toBe('abc')
        ->and(maskString('১২৩৪৫৬', 0, 2))->toBe('****৫৬');
});

it('normalises bangladeshi mobile numbers', function (string $input, ?string $local) {
    expect(formatBdPhone($input))->toBe($local)
        ->and(isBdMobile($input))->toBe($local !== null);
})->with([
    'local' => ['01712345678', '01712345678'],
    'without leading zero' => ['1712345678', '01712345678'],
    'international' => ['+8801712345678', '01712345678'],
    'country code without plus' => ['8801712345678', '01712345678'],
    'double zero prefix' => ['008801712345678', '01712345678'],
    'spaces and dashes' => ['+880 1712-345 678', '01712345678'],
    'too short' => ['0171234567', null],
    'too long' => ['017123456789', null],
    'bad operator prefix' => ['01212345678', null],
    'landline' => ['029876543', null],
    'empty' => ['', null],
]);

it('formats bangladeshi mobile numbers in each style', function () {
    expect(formatBdPhone('01712345678', 'international'))->toBe('+8801712345678')
        ->and(formatBdPhone('01712345678', 'dashed'))->toBe('01712-345678')
        ->and(formatBdPhone('0121234567', 'international'))->toBeNull()
        ->and(fn () => formatBdPhone('01712345678', 'pretty'))->toThrow(InvalidArgumentException::class);
});

it('calculates a folder size recursively', function () {
    $dir = sys_get_temp_dir().'/larahelpers-'.uniqid();
    mkdir("{$dir}/nested", 0777, true);
    file_put_contents("{$dir}/a.txt", str_repeat('a', 100));
    file_put_contents("{$dir}/nested/b.txt", str_repeat('b', 50));

    expect(getFolderSize($dir))->toBe(150);

    unlink("{$dir}/nested/b.txt");
    unlink("{$dir}/a.txt");
    rmdir("{$dir}/nested");
    rmdir($dir);
});

it('formats byte sizes', function () {
    expect(getFormatSize(-1))->toBe('Invalid size')
        ->and(getFormatSize(500))->toBe('500 B')
        ->and(getFormatSize(2048))->toBe('2 KB')
        ->and(getFormatSize(1536))->toBe('2 KB')
        ->and(getFormatSize(5 * 1024 * 1024))->toBe('5 MB')
        ->and(getFormatSize(3 * 1024 ** 3))->toBe('3 GB')
        ->and(getFormatSize(2 * 1024 ** 4))->toBe('2 TB');
});

it('detects the app device from the user agent', function (?string $agent, ?int $device) {
    app()->instance('request', Request::create('/', 'GET', server: $agent ? ['HTTP_USER_AGENT' => $agent] : []));

    expect(getCheckDevice())->toBe($device);
})->with([
    'android' => ['app-android', 1],
    'ios' => ['app-ios', 2],
    'windows' => ['app-windows', 3],
    'browser' => ['Mozilla/5.0', null],
    'none' => [null, null],
]);

it('generates an indentation prefix', function () {
    expect(getGenerateDepth(3))->toBe('---')
        ->and(getGenerateDepth(2, '>'))->toBe('>>')
        ->and(getGenerateDepth(0))->toBe('');
});

it('converts pipe strings to arrays', function () {
    expect(convertPipeToArray('one|two|three'))->toBe(['one', 'two', 'three'])
        ->and(convertPipeToArray('"one|two"'))->toBe(['one', 'two'])
        ->and(convertPipeToArray("'a,b'", ','))->toBe(['a', 'b'])
        ->and(convertPipeToArray(' ab '))->toBe('ab');
});
