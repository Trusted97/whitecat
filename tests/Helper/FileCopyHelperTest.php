<?php

namespace Whitecat\Test\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\FileCopyHelper;

class FileCopyHelperTest extends TestCase
{
    private FileCopyHelper $fileCopyHelper;

    protected function setUp(): void
    {
        $input                = $this->getMockBuilder(InputInterface::class)->getMock();
        $output               = $this->getMockBuilder(OutputInterface::class)->getMock();
        $this->fileCopyHelper = new FileCopyHelper(new SymfonyStyle($input, $output), new Filesystem());
    }

    public function testConstruct(): void
    {
        $this->assertNotNull($this->fileCopyHelper);
        $this->assertInstanceOf(FileCopyHelper::class, $this->fileCopyHelper);
    }
}
