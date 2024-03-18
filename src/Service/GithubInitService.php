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

class GithubInitService
{
    protected readonly string $githubDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->githubDirectoryPath = Path::normalize(DirectoryPath::GITHUB->value);
        $this->fileCopyHelper      = new FileCopyHelper($this->io, $this->fs);
    }

    public function run(): int
    {
        $this->io->title('Github init');

        $this->addGithubDirectory();
        $this->addReadme();
        $this->addEditorConfig();
        $this->addGitAttributes();
        $this->addGitIgnore();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CoversNothing]
    protected function addGithubDirectory(): void
    {
        $githubDirectoryExists = $this->fs->exists($this->githubDirectoryPath);
        $override              = true;

        if ($githubDirectoryExists) {
            $override = $this->io->confirm(
                question: 'It seems that github directory already exists, do you want to continue?',
                default: false
            );
        }

        if ($override) {
            try {
                $this->fs->mkdir($this->githubDirectoryPath);
                $this->io->comment('Github directory created');
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
            $this->io->comment('Skipped creation of Github directory');
        }
    }

    #[CoversNothing]
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

    #[CoversNothing]
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

    #[CoversNothing]
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

    #[CoversNothing]
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
