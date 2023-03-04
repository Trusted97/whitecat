<?php

namespace Whitecat\Test\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\CopyHelper;

class CopyHelperTest extends TestCase
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
        $copyWorkflowHelper = new CopyHelper($this->symfonyStyle, $this->filesystem);
        $this->assertNotNull($copyWorkflowHelper);
        $this->assertInstanceOf(CopyHelper::class, $copyWorkflowHelper);
    }
}
