<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpCsFixerService;

#[AsCommand(
    name: 'php-cs-fixer:init',
    description: 'Setup basic php-cs-fixer for PHP library',
    hidden: false
)]
class PhpCsFixerCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new PhpCsFixerService($io, $filesystem, $workingDirectory))->run();
    }
}
