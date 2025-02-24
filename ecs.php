<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
    ])

    ->withPreparedSets(
         arrays: true,
        comments: true,
        docblocks: true,
        spaces: true,
        namespaces: true,
        strict: true
     )
     
     ;
