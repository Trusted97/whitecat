<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubIssueService;

class GithubIssueServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $githubIssueService = new GithubIssueService($mockIo, $mockFs);
        $this->assertInstanceOf(GithubIssueService::class, $githubIssueService);
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
            ->with('Github issue');

        $mockFs->expects($this->once())
            ->method('mkdir');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        // Create GithubIssueService instance
        $githubIssueService = new GithubIssueService($mockIo, $mockFs);

        // Run the method
        $result = $githubIssueService->run();

        // Assertions
        $this->assertSame(Command::SUCCESS, $result);
    }
}
