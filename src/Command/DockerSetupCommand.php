<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\DockerSetupService;

#[AsCommand(
    name: 'docker:setup',
    description: 'Generate basic docker setup for PHP library',
    hidden: false
)]
class DockerSetupCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new DockerSetupService($io, $filesystem, $workingDirectory))->run();
    }
}
