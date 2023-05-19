<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\PhpCsFixerCommand;

class PhpCsFixerCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $application = new Application();
        $application->add(new PhpCsFixerCommand());
        $installerCommand = $application->find('php-cs-fixer:init');

        $this->assertInstanceOf(Command::class, $installerCommand);

        $commandTester = new CommandTester($installerCommand);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            'Setup basic php-cs-fixer',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
