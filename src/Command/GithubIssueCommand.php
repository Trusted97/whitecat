<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubIssueService;

#[AsCommand(
    name: 'github:issue',
    description: 'Generate basic template for GitHub issue',
    hidden: false
)]
class GithubIssueCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new GithubIssueService($io, $filesystem, $workingDirectory))->run();
    }
}
