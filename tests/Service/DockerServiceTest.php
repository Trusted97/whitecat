<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\DockerService;

class DockerServiceTest extends TestCase
{
    private SymfonyStyle $symfonyStyle;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        $input                    = $this->getMockBuilder(InputInterface::class)->getMock();
        $output                   = $this->getMockBuilder(OutputInterface::class)->getMock();
        $this->symfonyStyle       = new SymfonyStyle($input, $output);
        $this->filesystem         = new Filesystem();
    }

    public function testConstruct(): void
    {
        $dockerService = new DockerService($this->symfonyStyle, $this->filesystem);
        $this->assertNotNull($dockerService);
        $this->assertInstanceOf(DockerService::class, $dockerService);
    }

    public function testRun(): void
    {
        $dockerService = new DockerService($this->symfonyStyle, $this->filesystem);
        $statusCode    = $dockerService->run();
        $this->assertNotNull($statusCode);
        $this->assertSame(0, $statusCode);
    }
}
