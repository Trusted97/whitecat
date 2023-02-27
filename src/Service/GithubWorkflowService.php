<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class GithubWorkflowService
{
    protected readonly string $workflowPath;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->workflowPath = Path::normalize('.github/workflows/');
    }

    public function run(): int
    {
        $this->io->title('Github workflow');

        $this->addGithubWorkflowDirectory();

        $choice = $this->io->choice(
            question: 'Select the workflow to create',
            choices: [
                'PHPUnit & Coverage (CodeCov)',
                'Deploy to Amazon ECS',
                'Deploy to Google Kubernetes Engine',
                'Terraform Deploy',
                'All',
            ]
        );

        switch ($choice) {
            case 'PHPUnit & Coverage (CodeCov)':
                $this->addGithubTestAction();
                $this->io->warning('Remember to add your Codecov Token in Github Secrets');
                break;
            case 'Deploy to Amazon ECS':
                $this->addAmazonECSDeployAction();
                break;
            case 'Deploy to Google Kubernetes Engine':
                $this->addGoogleGKEDeployAction();
                break;
            case 'Terraform Deploy':
                $this->addTerraformDeployAction();
                break;
            case 'All':
                $this->addGithubTestAction();
                $this->addAmazonECSDeployAction();
                $this->addGoogleGKEDeployAction();
                $this->addTerraformDeployAction();
                break;
        }

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CodeCoverageIgnore]
    protected function setupAndCopyAction(
        string $fileName,
        string $questionMessage,
        string $overrideCommentMessage,
        string $skippedMessage
    ): void {
        $checkExists = $this->fs->exists($this->workflowPath . $fileName);
        $override    = true;

        if ($checkExists) {
            $override = $this->io->confirm(
                question: $questionMessage,
                default: false
            );
        }

        if ($override) {
            $this->io->comment($overrideCommentMessage);
            $this->fs->copy(
                originFile: Path::normalize('dist/workflows/' . $fileName),
                targetFile: $this->workflowPath . $fileName,
                overwriteNewerFiles: true
            );
        } else {
            $this->io->comment($skippedMessage);
        }
    }

    #[CodeCoverageIgnore]
    private function addGithubWorkflowDirectory(): void
    {
        $workflowDirectoryExists = $this->fs->exists($this->workflowPath);
        $override                = true;

        if ($workflowDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that github workflow directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->workflowPath);
                $this->io->comment('Github workflow directory created');
            } catch (IOExceptionInterface $IOException) {
                $this->io->error(
                    \sprintf(
                        'An error occurred while creating your directory at %s',
                        $IOException->getPath()
                    )
                );
            } catch (\Exception $commandException) {
                $this->io->error(
                    \sprintf(
                        'An error occurred while executing github:workflow command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of github workflow directory');
        }
    }

    #[CodeCoverageIgnore]
    private function addGithubTestAction(): void
    {
        $this->setupAndCopyAction(
            fileName: 'test.yaml',
            questionMessage: 'It seems that github action for test already exists, do you want to override?',
            overrideCommentMessage: 'Adding github action for phpunit and code coverage',
            skippedMessage: 'Skipped creation of github action for phpunit and code coverage'
        );
    }

    #[CodeCoverageIgnore]
    private function addGoogleGKEDeployAction(): void
    {
        $this->setupAndCopyAction(
            fileName: 'deploy_google_gke.yaml',
            questionMessage: 'It seems that github action for deploy on Google Kubernetes Engine already exists, do you want to override?',
            overrideCommentMessage: 'Adding Google GKE Deploy action',
            skippedMessage: 'Skipped creation of Google GKE Deploy action'
        );
    }

    #[CodeCoverageIgnore]
    private function addAmazonECSDeployAction(): void
    {
        $this->setupAndCopyAction(
            fileName: 'deploy_aws_ecs.yaml',
            questionMessage: 'It seems that github action for deploy on Amazon ECS already exists, do you want to override?',
            overrideCommentMessage: 'Adding Amazon ECS Deploy action',
            skippedMessage: 'Skipped creation of Amazon ECS Deploy action'
        );
    }

    #[CodeCoverageIgnore]
    private function addTerraformDeployAction(): void
    {
        $this->setupAndCopyAction(
            fileName: 'terraform.yaml',
            questionMessage: 'It seems that github action for deploy on terraform already exists, do you want to override?',
            overrideCommentMessage: 'Adding Terraform Deploy action',
            skippedMessage: 'Skipped creation of Terraform Deploy action'
        );
    }
}
