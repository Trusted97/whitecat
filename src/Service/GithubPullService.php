<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\FileCopyHelper;

class GithubPullService
{
    protected readonly string $githubPullDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->githubPullDirectoryPath = Path::normalize(DirectoryPath::PULL->value);
        $this->fileCopyHelper          = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
    }

    public function run(): int
    {
        $this->io->title('GitHub pull');

        $this->addGithubPullDirectory();
        $this->addFixPullRequestTemplate();
        $this->addImprovementPullRequestTemplate();
        $this->addNewFeaturePullRequestTemplate();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addGithubPullDirectory(): void
    {
        $githubPullDirectoryTarget    = Path::join($this->workingDirectory, $this->githubPullDirectoryPath);
        $githubIssueDirectoryExists   = $this->fs->exists($githubPullDirectoryTarget);
        $override                     = true;

        if ($githubIssueDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that GitHub pull request directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($githubPullDirectoryTarget);
                $this->io->comment('GitHub pull request directory created');
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
                        'An error occurred while executing github:pull command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of GitHub pull request directory');
        }
    }

    protected function addFixPullRequestTemplate(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'FIX.md',
            questionMessage: 'It seems that a fix pull request template already exists, do you want to override?',
            overrideCommentMessage: 'Adding FIX.md',
            skippedMessage: 'Skipped creation of FIX.md',
            sourceFileDirectory: $this->githubPullDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_PULL->value
        );
    }

    protected function addImprovementPullRequestTemplate(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'IMPROVEMENT.md',
            questionMessage: 'It seems that a improvement pull request template already exists, do you want to override?',
            overrideCommentMessage: 'Adding IMPROVEMENT.md',
            skippedMessage: 'Skipped creation of IMPROVEMENT.md',
            sourceFileDirectory: $this->githubPullDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_PULL->value
        );
    }

    protected function addNewFeaturePullRequestTemplate(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'NEW_FEATURE.md',
            questionMessage: 'It seems that a new feature pull request template already exists, do you want to override?',
            overrideCommentMessage: 'Adding NEW_FEATURE.md',
            skippedMessage: 'Skipped creation of NEW_FEATURE.md',
            sourceFileDirectory: $this->githubPullDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_PULL->value
        );
    }
}
