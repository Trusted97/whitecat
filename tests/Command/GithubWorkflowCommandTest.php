<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\GithubWorkflowCommand;

class GithubWorkflowCommandTest extends TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new GithubWorkflowCommand());

        $installerCommand = $application->find('github:workflow');

        $this->assertInstanceOf(Command::class, $installerCommand);

        $commandTester = new CommandTester($installerCommand);
        $commandTester->execute([]);

        $this->assertStringContainsString(
            'Github workflow',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
