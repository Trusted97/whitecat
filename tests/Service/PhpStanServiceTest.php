<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Helper\FileCopyHelper;
use Whitecat\Service\PhpCsFixerService;
use Whitecat\Service\PhpStanService;

class PhpStanServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phpStanService = new PhpStanService($mockIo, $mockFs);
        $this->assertInstanceOf(PhpStanService::class, $phpStanService);
    }

    public function testFailedRun(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Setup basic PHPStan');

        // Mock ComposerHelper
        $mockComposerHelper = $this->getMockBuilder(ComposerHelper::class)
            ->getMock();

        $mockComposerHelper->expects($this->once())
            ->method('getComposerContent')
            ->with('./composer.json')
            ->willReturn(['require-dev' => []]);

        $mockIo->expects($this->never())
            ->method('error');

        // Create PhpCsFixerService instance
        $phpStanService              = new PhpStanService($mockIo, $mockFs);
        $phpStanServiceReflection    = new \ReflectionClass(PhpStanService::class);
        $composerHelperProperty      = $phpStanServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setAccessible(true);
        $composerHelperProperty->setValue($phpStanService, $mockComposerHelper);

        // Run the method
        $result = $phpStanService->run();

        // Assertions
        $this->assertSame(Command::FAILURE, $result);
    }

    public function testSuccessRun(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Setup basic PHPStan');

        // Mock ComposerHelper
        $mockComposerHelper = $this->getMockBuilder(ComposerHelper::class)
            ->getMock();

        $mockComposerHelper->expects($this->once())
            ->method('getComposerContent')
            ->willReturn(['require-dev' => [
                'phpstan/phpstan' => '^1.10',
            ]]);

        $mockIo->expects($this->never())
            ->method('error');

        // Mock FileCopyHelper
        $mockFileCopyHelper = $this->getMockBuilder(FileCopyHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFileCopyHelper->expects($this->once())
            ->method('copyFile')
            ->with(
                'phpstan.neon',
                'It seems that a phpstan.neon already exists, do you want to override?',
                'Adding phpstan.neon',
                'Skipped creation of phpstan.neon',
                '',
                'dist/'
            );

        // Create PhpCsFixerService instance
        $phpStanService              = new PhpStanService($mockIo, $mockFs);
        $phpStanServiceReflection    = new \ReflectionClass(PhpStanService::class);
        $composerHelperProperty      = $phpStanServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setAccessible(true);
        $composerHelperProperty->setValue($phpStanService, $mockComposerHelper);
        $fileCopyHelperProperty = $phpStanServiceReflection->getProperty('fileCopyHelper');
        $fileCopyHelperProperty->setAccessible(true);
        $fileCopyHelperProperty->setValue($phpStanService, $mockFileCopyHelper);

        // Run the method
        $result = $phpStanService->run();

        // Assertions
        $this->assertSame(Command::SUCCESS, $result);
    }
}
