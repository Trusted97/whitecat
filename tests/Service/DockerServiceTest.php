<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\DockerService;

class DockerServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dockerService = new DockerService($mockIo, $mockFs);
        $this->assertNotNull($dockerService);
        $this->assertInstanceOf(DockerService::class, $dockerService);
    }

    public function testRun(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Docker setup');
        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $mockFs->expects($this->once())
            ->method('mkdir');

        // Create DockerService instance
        $dockerService = new DockerService($mockIo, $mockFs);

        // Run the method
        $result = $dockerService->run();

        // Assertions
        $this->assertSame(Command::SUCCESS, $result);
    }
}
