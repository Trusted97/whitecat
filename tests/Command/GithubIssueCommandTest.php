<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\GithubIssueCommand;

class GithubIssueCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $application = new Application();
        $application->add(new GithubIssueCommand());
        $installerCommand = $application->find('github:issue');

        $this->assertInstanceOf(Command::class, $installerCommand);

        $commandTester = new CommandTester($installerCommand);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            'Github issue',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
