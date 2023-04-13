<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\GithubPullCommand;

class GithubPullCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $application = new Application();
        $application->add(new GithubPullCommand());
        $installerCommand = $application->find('github:pull');

        $this->assertInstanceOf(Command::class, $installerCommand);

        $commandTester = new CommandTester($installerCommand);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            'Github pull',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
