<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Helper\DirectoryCopyHelper;
use Whitecat\Helper\FileCopyHelper;
use Whitecat\Service\PhpUnitService;

class PhpUnitServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phpCsFixerService = new PhpUnitService($mockIo, $mockFs);
        $this->assertInstanceOf(PhpUnitService::class, $phpCsFixerService);
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
            ->with('Setup basic PHPUnit');

        // Mock ComposerHelper
        $mockComposerHelper = $this->getMockBuilder(ComposerHelper::class)
            ->getMock();

        $mockComposerHelper->expects($this->once())
            ->method('getComposerContent')
            ->with('./composer.json')
            ->willReturn(['require-dev' => [
                'phpunit/phpunit' => '^10',
            ]]);

        $mockIo->expects($this->never())
            ->method('error');

        $mockIo->expects($this->never())
            ->method('warning');

        // Mock FileCopyHelper
        $mockFileCopyHelper = $this->getMockBuilder(FileCopyHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFileCopyHelper->expects($this->once())
            ->method('copyFile')
            ->with(
                'phpunit.xml',
                'It seems that a phpunit.xml already exists, do you want to override?',
                'Adding phpunit.xml',
                'Skipped creation of phpunit.xml',
                '',
                'dist/'
            );

        // Mock DirectoryCopyHelper
        $mockDirectoryCopyHelper = $this->getMockBuilder(DirectoryCopyHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockDirectoryCopyHelper->expects($this->once())
            ->method('copyDirectory')
            ->with(
                'It seems that config for PHPUnit already exists, do you want to override?',
                'Adding PHPUnit config',
                'Skipped creation of PHPUnit config',
                'tests',
                'dist/tests/'
            );

        // Create PhpUnitService instance and inject dependencies
        $phpUnitService           = new PhpUnitService($mockIo, $mockFs);
        $phpUnitServiceReflection = new \ReflectionClass(PhpUnitService::class);
        $composerHelperProperty   = $phpUnitServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setValue($phpUnitService, $mockComposerHelper);
        $fileCopyHelperProperty = $phpUnitServiceReflection->getProperty('fileCopyHelper');
        $fileCopyHelperProperty->setValue($phpUnitService, $mockFileCopyHelper);
        $directoryCopyHelperProperty = $phpUnitServiceReflection->getProperty('directoryCopyHelper');
        $directoryCopyHelperProperty->setValue($phpUnitService, $mockDirectoryCopyHelper);

        // Run the method
        $result = $phpUnitService->run();

        // Assertions
        $this->assertSame(Command::SUCCESS, $result);
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
            ->with('Setup basic PHPUnit');

        // Mock ComposerHelper
        $mockComposerHelper = $this->getMockBuilder(ComposerHelper::class)
            ->getMock();

        $mockComposerHelper->expects($this->once())
            ->method('getComposerContent')
            ->willReturn(['require-dev' => []]);

        // Mock FileCopyHelper
        $mockFileCopyHelper = $this->getMockBuilder(FileCopyHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFileCopyHelper->expects($this->never())->method('copyFile');

        // Mock DirectoryCopyHelper
        $mockDirectoryCopyHelper = $this->getMockBuilder(DirectoryCopyHelper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockDirectoryCopyHelper->expects($this->never())->method('copyDirectory');

        // Create PhpUnitService instance and inject dependencies
        $phpUnitService           = new PhpUnitService($mockIo, $mockFs);
        $phpUnitServiceReflection = new \ReflectionClass(PhpUnitService::class);
        $composerHelperProperty   = $phpUnitServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setValue($phpUnitService, $mockComposerHelper);
        $fileCopyHelperProperty = $phpUnitServiceReflection->getProperty('fileCopyHelper');
        $fileCopyHelperProperty->setValue($phpUnitService, $mockFileCopyHelper);
        $directoryCopyHelperProperty = $phpUnitServiceReflection->getProperty('directoryCopyHelper');
        $directoryCopyHelperProperty->setValue($phpUnitService, $mockDirectoryCopyHelper);

        // Run the method
        $result = $phpUnitService->run();

        // Assertions
        $this->assertSame(Command::FAILURE, $result);
    }
}
