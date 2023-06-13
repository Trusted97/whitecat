<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\GithubInitCommand;

class GithubInitCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $application = new Application();
        $application->add(new GithubInitCommand());
        $installerCommand = $application->find('github:init');

        $this->assertInstanceOf(Command::class, $installerCommand);

        $commandTester = new CommandTester($installerCommand);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            'Github init',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
