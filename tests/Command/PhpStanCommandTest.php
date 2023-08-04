<?php

namespace Whitecat\Test\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Whitecat\Command\PhpStanCommand;

class PhpStanCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $application = new Application();
        $application->add(new PhpStanCommand());
        $phpStanCommand = $application->find('phpstan:init');

        $this->assertInstanceOf(Command::class, $phpStanCommand);

        $commandTester = new CommandTester($phpStanCommand);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString(
            'Setup basic PHPStan',
            $commandTester->getDisplay()
        );

        $this->assertSame(0, $commandTester->getStatusCode());
    }
}
