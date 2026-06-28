<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubWorkflowService;

#[AsCommand(
    name: 'github:workflow',
    description: 'Generate basic workflow for PHP library',
    hidden: false
)]
class GithubWorkflowCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new GithubWorkflowService($io, $filesystem, $workingDirectory))->run();
    }
}
