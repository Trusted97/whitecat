<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpUnitService;

class PhpUnitServiceTest extends TestCase
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
        $phpUnitService = new PhpUnitService($this->symfonyStyle, $this->filesystem);
        $this->assertNotNull($phpUnitService);
        $this->assertInstanceOf(PhpUnitService::class, $phpUnitService);
    }

    public function testRun(): void
    {
        $phpUnitService        = new PhpUnitService($this->symfonyStyle, $this->filesystem);
        $statusCode            = $phpUnitService->run();
        $this->assertNotNull($statusCode);
        $this->assertSame(0, $statusCode);
    }
}
