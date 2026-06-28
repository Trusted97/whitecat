<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\FileCopyHelper;

class GithubWorkflowService
{
    protected readonly string $workflowDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->workflowDirectoryPath = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->fileCopyHelper        = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
    }

    public function run(): int
    {
        $this->io->title('GitHub workflow');

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
                $this->io->warning('Remember to add your Codecov Token in GitHub Secrets');
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

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addGithubWorkflowDirectory(): void
    {
        $workflowDirectoryTarget = Path::join($this->workingDirectory, $this->workflowDirectoryPath);
        $workflowDirectoryExists = $this->fs->exists($workflowDirectoryTarget);
        $override                = true;

        if ($workflowDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that GitHub workflow directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($workflowDirectoryTarget);
                $this->io->comment('GitHub workflow directory created');
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
            $this->io->comment('Skipped creation of GitHub workflow directory');
        }
    }

    protected function addGithubTestAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'test.yaml',
            questionMessage: 'It seems that GitHub action for test already exists, do you want to override?',
            overrideCommentMessage: 'Adding GitHub action for phpunit and code coverage',
            skippedMessage: 'Skipped creation of GitHub action for phpunit and code coverage',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    protected function addGoogleGKEDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'deploy_google_gke.yaml',
            questionMessage: 'It seems that GitHub action for deploy on Google Kubernetes Engine already exists, do you want to override?',
            overrideCommentMessage: 'Adding Google GKE Deploy action',
            skippedMessage: 'Skipped creation of Google GKE Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    protected function addAmazonECSDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'deploy_aws_ecs.yaml',
            questionMessage: 'It seems that GitHub action for deploy on Amazon ECS already exists, do you want to override?',
            overrideCommentMessage: 'Adding Amazon ECS Deploy action',
            skippedMessage: 'Skipped creation of Amazon ECS Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    protected function addTerraformDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'terraform.yaml',
            questionMessage: 'It seems that GitHub action for deploy on terraform already exists, do you want to override?',
            overrideCommentMessage: 'Adding Terraform Deploy action',
            skippedMessage: 'Skipped creation of Terraform Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }
}
