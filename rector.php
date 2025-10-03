<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,     // adapte ton code au PHP moderne
        SymfonySetList::SYMFONY_40,     // migration vers Symfony 4
        SymfonySetList::SYMFONY_50,     // migration vers Symfony 5
        SymfonySetList::SYMFONY_60,     // migration vers Symfony 6
        SymfonySetList::SYMFONY_70,     // migration vers Symfony 7
    ]);
};
