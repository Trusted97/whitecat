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

class GithubPullService
{
    protected readonly string $githubPullDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
    ) {
        $this->githubPullDirectoryPath = Path::normalize(DirectoryPath::PULL->value);
        $this->fileCopyHelper          = new FileCopyHelper($this->io, $this->fs);
    }

    public function run(): int
    {
        $this->io->title('Github pull');

        $this->addGithubPullDirectory();
        $this->addFixPullRequestTemplate();
        $this->addImprovementPullRequestTemplate();
        $this->addNewFeaturePullRequestTemplate();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CoversNothing]
    protected function addGithubPullDirectory(): void
    {
        $githubIssueDirectoryExists   = $this->fs->exists($this->githubPullDirectoryPath);
        $override                     = true;

        if ($githubIssueDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that github pull request directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->githubPullDirectoryPath);
                $this->io->comment('Github pull request directory created');
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
            $this->io->comment('Skipped creation of Github pull request directory');
        }
    }

    #[CoversNothing]
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

    #[CoversNothing]
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

    #[CoversNothing]
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
