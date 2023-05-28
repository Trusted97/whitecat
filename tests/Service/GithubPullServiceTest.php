<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubPullService;

class GithubPullServiceTest extends TestCase
{
    public function testConstruct(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $githubIssueService = new GithubPullService($mockIo, $mockFs);
        $this->assertNotNull($githubIssueService);
        $this->assertInstanceOf(GithubPullService::class, $githubIssueService);
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
            ->with('Github pull');

        $mockFs->expects($this->once())
            ->method('mkdir');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $githubIssueService = new GithubPullService($mockIo, $mockFs);

        $result = $githubIssueService->run();

        $this->assertSame(Command::SUCCESS, $result);
    }
}
