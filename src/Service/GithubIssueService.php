<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\IgnoreFunctionForCodeCoverage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\CopyHelper;

#[IgnoreFunctionForCodeCoverage(functionName: 'addGithubIssueDirectory')]
#[IgnoreFunctionForCodeCoverage(functionName: 'addBugReportIssue')]
#[IgnoreFunctionForCodeCoverage(functionName: 'addFeatureRequestIssue')]
#[IgnoreFunctionForCodeCoverage(functionName: 'addIssueConfigFile')]
class GithubIssueService
{
    protected readonly string $githubIssueDirectoryPath;
    protected CopyHelper $copyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->githubIssueDirectoryPath   = Path::normalize(DirectoryPath::ISSUE->value);
        $this->copyHelper                 = new CopyHelper($this->io, $this->fs);
    }

    public function run(): int
    {
        $this->io->title('Github issue');

        $this->addGithubIssueDirectory();
        $this->addBugReportIssue();
        $this->addFeatureRequestIssue();
        $this->addIssueConfigFile();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    private function addGithubIssueDirectory(): void
    {
        $githubIssueDirectoryExists   = $this->fs->exists($this->githubIssueDirectoryPath);
        $override                     = true;

        if ($githubIssueDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that github issue directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->githubIssueDirectoryPath);
                $this->io->comment('Github issue directory created');
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
            $this->io->comment('Skipped creation of Github issue directory');
        }
    }

    private function addBugReportIssue(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: 'BUG-REPORT.yml',
            questionMessage: 'It seems that a bug report issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding BUG-REPORT.yml',
            skippedMessage: 'Skipped creation of BUG-REPORT.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }

    private function addFeatureRequestIssue(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: 'FEATURE-REQUEST.yml',
            questionMessage: 'It seems that a feature request issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding FEATURE-REQUEST.yml',
            skippedMessage: 'Skipped creation of FEATURE-REQUEST.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }

    private function addIssueConfigFile(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: 'config.yml',
            questionMessage: 'It seems that a config file for issue already exists, do you want to override?',
            overrideCommentMessage: 'Adding config.yml',
            skippedMessage: 'Skipped creation of config.yml',
            sourceFileDirectory: $this->githubIssueDirectoryPath,
            distFileDirectory: DirectoryPath::DIST_ISSUE->value
        );
    }
}
