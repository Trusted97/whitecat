<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\FileCopyHelper;

class GithubInitService
{
    protected readonly string $githubDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->githubDirectoryPath = Path::normalize(DirectoryPath::GITHUB->value);
        $this->fileCopyHelper      = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
    }

    public function run(): int
    {
        $this->io->title('GitHub init');

        $this->addGithubDirectory();
        $this->addReadme();
        $this->addEditorConfig();
        $this->addGitAttributes();
        $this->addGitIgnore();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addGithubDirectory(): void
    {
        $githubDirectoryTarget = Path::join($this->workingDirectory, $this->githubDirectoryPath);
        $githubDirectoryExists = $this->fs->exists($githubDirectoryTarget);
        $override              = true;

        if ($githubDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that GitHub directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($githubDirectoryTarget);
                $this->io->comment('GitHub directory created');
            } catch (IOExceptionInterface $IOException) {
                $this->io->error(
                    \sprintf(
                        'Error: %s',
                        $IOException->getMessage()
                    )
                );
            } catch (\Exception $commandException) {
                $this->io->error(
                    \sprintf(
                        'An error occurred while executing github:init command %s',
                        $commandException->getMessage()
                    )
                );
            }
        } else {
            $this->io->comment('Skipped creation of GitHub directory');
        }
    }

    protected function addEditorConfig(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: '.editorconfig',
            questionMessage: 'It seems that a .editorconfig already exists, do you want to override?',
            overrideCommentMessage: 'Adding .editorconfig',
            skippedMessage: 'Skipped creation of .editorconfig',
            sourceFileDirectory: '',
            distFileDirectory: Path::normalize(DirectoryPath::DIST_GITHUB->value)
        );
    }

    protected function addGitAttributes(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: '.gitattributes',
            questionMessage: 'It seems that a .gitattributes already exists, do you want to override?',
            overrideCommentMessage: 'Adding .gitattributes',
            skippedMessage: 'Skipped creation of .gitattributes',
            sourceFileDirectory: '',
            distFileDirectory: Path::normalize(DirectoryPath::DIST_GITHUB->value)
        );
    }

    protected function addGitIgnore(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: '.gitignore',
            questionMessage: 'It seems that a .gitignore already exists, do you want to override?',
            overrideCommentMessage: 'Adding .gitignore',
            skippedMessage: 'Skipped creation of .gitignore',
            sourceFileDirectory: '',
            distFileDirectory: Path::normalize(DirectoryPath::DIST_GITHUB->value)
        );
    }

    protected function addReadme(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'README.md',
            questionMessage: 'It seems that a README.md already exists, do you want to override?',
            overrideCommentMessage: 'Adding README.md',
            skippedMessage: 'Skipped creation of README.md',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_GITHUB->value
        );
    }
}
