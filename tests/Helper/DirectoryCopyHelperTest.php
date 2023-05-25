<?php

namespace Whitecat\Test\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\FileCopyHelper;

class DirectoryCopyHelperTest extends TestCase
{
    public function testConstruct(): void
    {
        $ioMock = $this->getMockBuilder(SymfonyStyle::class)->disableOriginalConstructor()->getMock();
        $fsMock = $this->getMockBuilder(Filesystem::class)->getMock();

        $fileCopyHelper = new FileCopyHelper($ioMock, $fsMock);

        $this->assertNotNull($fileCopyHelper);
        $this->assertInstanceOf(FileCopyHelper::class, $fileCopyHelper);
    }
}
