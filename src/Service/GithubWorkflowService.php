<?php

namespace Whitecat\Service;

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
        $this->workflowPath = Path::normalize('.github/workflow/');
    }

    public function run(): int
    {
        $this->io->title('Github workflow');

        $this->addGithubWorkflowDirectory();
        $this->addGithubTestAction();

        return Command::SUCCESS;
    }

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

    private function addGithubTestAction(): void
    {
        $githubTestActionExists = $this->fs->exists($this->workflowPath . 'test.yaml');
        $override               = true;

        if ($githubTestActionExists) {
            $override = $this->io->confirm(
                question: 'It seems that github action for test already exists, do you want to override?',
                default: false
            );
        }

        if ($override) {
            $this->io->comment('Adding Github Action for PHPUnit Test and code coverage');
            $this->fs->copy(
                originFile: Path::normalize('dist/workflow/test.yaml'),
                targetFile: $this->workflowPath . 'test.yaml',
                overwriteNewerFiles: true
            );
            $this->io->warning('Remember to add your Codecov Token in Github Secrets');
        }
    }
}
