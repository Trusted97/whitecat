<?php

namespace Whitecat\Console;

use Symfony\Component\Console\Application;
use Whitecat\Command\DockerSetupCommand;
use Whitecat\Command\GithubInitCommand;
use Whitecat\Command\GithubIssueCommand;
use Whitecat\Command\GithubPullCommand;
use Whitecat\Command\GithubWorkflowCommand;
use Whitecat\Command\PhpCsFixerCommand;
use Whitecat\Command\PhpStanCommand;
use Whitecat\Command\PhpUnitCommand;

final class WhitecatApplicationFactory
{
    public static function create(): Application
    {
        $application = new Application('whitecat', self::version());
        $application->addCommands([
            new DockerSetupCommand(),
            new GithubInitCommand(),
            new GithubIssueCommand(),
            new GithubPullCommand(),
            new GithubWorkflowCommand(),
            new PhpCsFixerCommand(),
            new PhpStanCommand(),
            new PhpUnitCommand(),
        ]);

        return $application;
    }

    private static function version(): string
    {
        return '1.x-dev';
    }
}
