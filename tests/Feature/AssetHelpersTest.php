<?php

it('builds storage asset urls', function () {
    expect(asset_storage('uploads/file.jpg'))->toBe('http://localhost/storage/uploads/file.jpg');
});

it('builds branding asset urls from config with an optional override', function (string $helper, string $configKey) {
    config()->set("app.{$configKey}", "images/{$configKey}.png");

    expect($helper())->toBe("http://localhost/images/{$configKey}.png")
        ->and($helper('custom/path.png'))->toBe('http://localhost/custom/path.png');
})->with([
    'favicon' => ['asset_favicon', 'favicon'],
    'logo' => ['asset_logo', 'logo'],
    'powered logo' => ['asset_powered_logo', 'logo_powered'],
    'dark logo' => ['asset_dark_logo', 'dark_logo'],
    'profile picture' => ['asset_profile_picture', 'default_profile_picture'],
]);
