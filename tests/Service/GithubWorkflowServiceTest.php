<?php

namespace Whitecat\Test\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Whitecat\Service\GithubWorkflowService;

class GithubWorkflowServiceTest extends TestCase
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
        $githubWorkflowService = new GithubWorkflowService($this->symfonyStyle, $this->filesystem);
        $this->assertInstanceOf(GithubWorkflowService::class, $githubWorkflowService);
    }

    public function testRunPHPUnitWorkflow(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Github workflow');

        $mockIo->expects($this->once())
            ->method('choice')
            ->with(
                $this->equalTo('Select the workflow to create'),
                $this->equalTo([
                    'PHPUnit & Coverage (CodeCov)',
                    'Deploy to Amazon ECS',
                    'Deploy to Google Kubernetes Engine',
                    'Terraform Deploy',
                    'All',
                ])
            )
            ->willReturn('PHPUnit & Coverage (CodeCov)');

        $mockIo->expects($this->once())
            ->method('warning')
            ->with('Remember to add your Codecov Token in Github Secrets');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $service = new GithubWorkflowService($mockIo, $mockFs);
        $result  = $service->run();

        $this->assertSame(Command::SUCCESS, $result);
    }

    public function testRunAmazonECSWorkflow(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Github workflow');

        $mockIo->expects($this->once())
            ->method('choice')
            ->with(
                $this->equalTo('Select the workflow to create'),
                $this->equalTo([
                    'PHPUnit & Coverage (CodeCov)',
                    'Deploy to Amazon ECS',
                    'Deploy to Google Kubernetes Engine',
                    'Terraform Deploy',
                    'All',
                ])
            )
            ->willReturn('Deploy to Amazon ECS');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new GithubWorkflowService($mockIo, $mockFs);
        $result  = $service->run();

        $this->assertSame(Command::SUCCESS, $result);
    }

    public function testRunGoogleKubernetesWorklow(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Github workflow');

        $mockIo->expects($this->once())
            ->method('choice')
            ->with(
                $this->equalTo('Select the workflow to create'),
                $this->equalTo([
                    'PHPUnit & Coverage (CodeCov)',
                    'Deploy to Amazon ECS',
                    'Deploy to Google Kubernetes Engine',
                    'Terraform Deploy',
                    'All',
                ])
            )
            ->willReturn('Deploy to Google Kubernetes Engine');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new GithubWorkflowService($mockIo, $mockFs);
        $result  = $service->run();

        $this->assertSame(Command::SUCCESS, $result);
    }

    public function testRunTerraformWorklow(): void
    {
        $mockIo = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIo->expects($this->once())
            ->method('title')
            ->with('Github workflow');

        $mockIo->expects($this->once())
            ->method('choice')
            ->with(
                $this->equalTo('Select the workflow to create'),
                $this->equalTo([
                    'PHPUnit & Coverage (CodeCov)',
                    'Deploy to Amazon ECS',
                    'Deploy to Google Kubernetes Engine',
                    'Terraform Deploy',
                    'All',
                ])
            )
            ->willReturn('Terraform Deploy');

        $mockIo->expects($this->once())
            ->method('success')
            ->with('All work was correctly done!');

        $mockFs = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new GithubWorkflowService($mockIo, $mockFs);
        $result  = $service->run();

        $this->assertSame(Command::SUCCESS, $result);
    }
}
