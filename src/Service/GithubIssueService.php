<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\FileCopyHelper;

class GithubIssueService
{
    protected readonly string $githubIssueDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->githubIssueDirectoryPath = Path::normalize(DirectoryPath::ISSUE->value);
        $this->fileCopyHelper           = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
    }

    public function run(): int
    {
        $this->io->title('GitHub issue');

        $this->addGithubIssueDirectory();
        $this->addBugReportIssue();
        $this->addFeatureRequestIssue();
        $this->addIssueConfigFile();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addGithubIssueDirectory(): void
    {
        $githubIssueDirectoryTarget   = Path::join($this->workingDirectory, $this->githubIssueDirectoryPath);
        $githubIssueDirectoryExists   = $this->fs->exists($githubIssueDirectoryTarget);
        $override                     = true;

        if ($githubIssueDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that GitHub issue directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($githubIssueDirectoryTarget);
                $this->io->comment('GitHub issue directory created');
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
                        'An error occurred while executing github:issue command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of GitHub issue directory');
        }
    }

    protected function addBugReportIssue(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'BUG-REPORT.yml',
            questionMessage: 'It seems that a bug report issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding BUG-REPORT.yml',
            skippedMessage: 'Skipped creation of BUG-REPORT.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }

    protected function addFeatureRequestIssue(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'FEATURE-REQUEST.yml',
            questionMessage: 'It seems that a feature request issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding FEATURE-REQUEST.yml',
            skippedMessage: 'Skipped creation of FEATURE-REQUEST.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }

    protected function addIssueConfigFile(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'config.yml',
            questionMessage: 'It seems that a config file for issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding config.yml',
            skippedMessage: 'Skipped creation of config.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }
}
