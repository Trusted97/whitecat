<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpStanService;

#[AsCommand(
    name: 'phpstan:init',
    description: 'Setup basic phpstan for PHP library',
    hidden: false
)]
class PhpStanCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new PhpStanService($io, $filesystem, $workingDirectory))->run();
    }
}
