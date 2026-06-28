<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpUnitService;

#[AsCommand(
    name: 'phpunit:init',
    description: 'Setup basic phpunit for PHP library',
    hidden: false
)]
class PhpUnitCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new PhpUnitService($io, $filesystem, $workingDirectory))->run();
    }
}
