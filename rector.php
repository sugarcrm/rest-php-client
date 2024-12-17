<?php

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withSkip([
        DisallowedEmptyRuleFixerRector::class,
        IssetOnPropertyObjectToPropertyExistsRector::class,
        SimplifyEmptyCheckOnEmptyArrayRector::class,
    ])
    ->withSets([
        SetList::PHP_74,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
//        SetList::TYPE_DECLARATION,
//        SetList::PRIVATIZATION,
    ])
    ->withImportNames(true, true, false, true);
