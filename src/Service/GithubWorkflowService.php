<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CoversNothing;
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
    ) {
        $this->workflowDirectoryPath = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->fileCopyHelper        = new FileCopyHelper($this->io, $this->fs);
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

    #[CoversNothing]
    protected function addGithubWorkflowDirectory(): void
    {
        $workflowDirectoryExists = $this->fs->exists($this->workflowDirectoryPath);
        $override                = true;

        if ($workflowDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that github workflow directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->workflowDirectoryPath);
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

    #[CoversNothing]
    protected function addGithubTestAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'test.yaml',
            questionMessage: 'It seems that github action for test already exists, do you want to override?',
            overrideCommentMessage: 'Adding github action for phpunit and code coverage',
            skippedMessage: 'Skipped creation of github action for phpunit and code coverage',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    #[CoversNothing]
    protected function addGoogleGKEDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'deploy_google_gke.yaml',
            questionMessage: 'It seems that github action for deploy on Google Kubernetes Engine already exists, do you want to override?',
            overrideCommentMessage: 'Adding Google GKE Deploy action',
            skippedMessage: 'Skipped creation of Google GKE Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    #[CoversNothing]
    protected function addAmazonECSDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'deploy_aws_ecs.yaml',
            questionMessage: 'It seems that github action for deploy on Amazon ECS already exists, do you want to override?',
            overrideCommentMessage: 'Adding Amazon ECS Deploy action',
            skippedMessage: 'Skipped creation of Amazon ECS Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }

    #[CoversNothing]
    protected function addTerraformDeployAction(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'terraform.yaml',
            questionMessage: 'It seems that github action for deploy on terraform already exists, do you want to override?',
            overrideCommentMessage: 'Adding Terraform Deploy action',
            skippedMessage: 'Skipped creation of Terraform Deploy action',
            sourceFileDirectory: $this->workflowDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_WORKFLOW->value
        );
    }
}
