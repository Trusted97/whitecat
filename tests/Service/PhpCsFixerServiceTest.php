<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\PhpCsFixerService;

class PhpCsFixerServiceTest extends TestCase
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
        $phpCsFixerService = new PhpCsFixerService($this->symfonyStyle, $this->filesystem);
        $this->assertNotNull($phpCsFixerService);
        $this->assertInstanceOf(PhpCsFixerService::class, $phpCsFixerService);
    }

    public function testRun(): void
    {
        $phpCsFixerService     = new PhpCsFixerService($this->symfonyStyle, $this->filesystem);
        $statusCode            = $phpCsFixerService->run();
        $this->assertNotNull($statusCode);
        $this->assertSame(0, $statusCode);
    }
}