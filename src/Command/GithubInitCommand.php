<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubInitService;

#[AsCommand(
    name: 'github:init',
    description: 'Generate basic GitHub directory with starter files',
    hidden: false
)]
class GithubInitCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new GithubInitService($io, $filesystem, $workingDirectory))->run();
    }
}
