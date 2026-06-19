<?php

namespace Whitecat\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubPullService;

#[AsCommand(
    name: 'github:pull',
    description: 'Generate basic template for GitHub pull request',
    hidden: false
)]
class GithubPullCommand extends AbstractWhitecatCommand
{
    protected function runCommand(SymfonyStyle $io, Filesystem $filesystem, string $workingDirectory): int
    {
        return (new GithubPullService($io, $filesystem, $workingDirectory))->run();
    }
}
