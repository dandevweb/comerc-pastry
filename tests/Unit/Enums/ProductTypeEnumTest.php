<?php

use App\Enums\ProductTypeEnum;

it('returns correct description for each product type', function () {
    expect(ProductTypeEnum::getDescription(ProductTypeEnum::salty->value))->toBe('Salgados');
    expect(ProductTypeEnum::getDescription(ProductTypeEnum::sweet->value))->toBe('Doces');
    expect(ProductTypeEnum::getDescription(ProductTypeEnum::drink->value))->toBe('Bebidas');
    expect(ProductTypeEnum::getDescription(999))->toBe(''); // invalid value
});

it('returns correct names for each product type', function () {
    $expectedNames = ['salty', 'sweet', 'drink'];
    expect(ProductTypeEnum::names())->toBe($expectedNames);
});

it('returns correct values for each product type', function () {
    $expectedValues = [1, 2, 3];
    expect(ProductTypeEnum::values())->toBe($expectedValues);
});

it('returns correct associative array for product type enum', function () {
    $expectedArray = [
        1 => 'salty',
        2 => 'sweet',
        3 => 'drink',
    ];
    expect(ProductTypeEnum::array())->toBe($expectedArray);
});
