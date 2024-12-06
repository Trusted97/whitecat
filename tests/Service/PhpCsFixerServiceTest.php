<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Helper\FileCopyHelper;
use Whitecat\Service\PhpCsFixerService;

class PhpCsFixerServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $phpCsFixerService = new PhpCsFixerService($mockIo, $mockFs);
        $this->assertInstanceOf(PhpCsFixerService::class, $phpCsFixerService);
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
            ->with('Setup basic php-cs-fixer');

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
        $phpCsFixerService           = new PhpCsFixerService($mockIo, $mockFs);
        $phpCsFixerServiceReflection = new \ReflectionClass(PhpCsFixerService::class);
        $composerHelperProperty      = $phpCsFixerServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setAccessible(true);
        $composerHelperProperty->setValue($phpCsFixerService, $mockComposerHelper);

        // Run the method
        $result = $phpCsFixerService->run();

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
            ->with('Setup basic php-cs-fixer');

        // Mock ComposerHelper
        $mockComposerHelper = $this->getMockBuilder(ComposerHelper::class)
            ->getMock();

        $mockComposerHelper->expects($this->once())
            ->method('getComposerContent')
            ->willReturn(['require-dev' => [
                'friendsofphp/php-cs-fixer' => '^3.14',
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
                '.php-cs-fixer.dist.php',
                'It seems that a .php-cs-fixer.dist.php already exists, do you want to override?',
                'Adding .php-cs-fixer.dist.php',
                'Skipped creation of .php-cs-fixer.dist.php',
                '',
                'dist/'
            );

        // Create PhpCsFixerService instance
        $phpCsFixerService           = new PhpCsFixerService($mockIo, $mockFs);
        $phpCsFixerServiceReflection = new \ReflectionClass(PhpCsFixerService::class);
        $composerHelperProperty      = $phpCsFixerServiceReflection->getProperty('composerHelper');
        $composerHelperProperty->setAccessible(true);
        $composerHelperProperty->setValue($phpCsFixerService, $mockComposerHelper);
        $fileCopyHelperProperty = $phpCsFixerServiceReflection->getProperty('fileCopyHelper');
        $fileCopyHelperProperty->setAccessible(true);
        $fileCopyHelperProperty->setValue($phpCsFixerService, $mockFileCopyHelper);

        // Run the method
        $result = $phpCsFixerService->run();

        // Assertions
        $this->assertSame(Command::SUCCESS, $result);
    }
}
