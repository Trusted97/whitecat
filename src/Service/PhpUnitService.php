<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\ComposerPackageChecker;
use Whitecat\Helper\DirectoryCopyHelper;
use Whitecat\Helper\FileCopyHelper;

class PhpUnitService
{
    protected readonly string $workflowDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;
    protected DirectoryCopyHelper $directoryCopyHelper;
    protected ComposerPackageChecker $composerPackageChecker;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->workflowDirectoryPath  = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->fileCopyHelper         = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
        $this->directoryCopyHelper    = new DirectoryCopyHelper($this->io, $this->fs, $this->workingDirectory);
        $this->composerPackageChecker = new ComposerPackageChecker();
    }

    public function run(): int
    {
        $this->io->title('Setup basic PHPUnit');

        if (Command::SUCCESS !== $this->composerPackageChecker->ensureDevPackageInstalled($this->io, $this->workingDirectory, 'phpunit/phpunit')) {
            return Command::FAILURE;
        }

        $this->addTestDirectory();
        $this->addPHPUnitConfigFile();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addPHPUnitConfigFile(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'phpunit.xml',
            questionMessage: 'It seems that a phpunit.xml already exists, do you want to override?',
            overrideCommentMessage: 'Adding phpunit.xml',
            skippedMessage: 'Skipped creation of phpunit.xml',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DIRECTORY->value
        );
    }

    protected function addTestDirectory(): void
    {
        $this->directoryCopyHelper->copyDirectory(
            questionMessage: 'It seems that config for PHPUnit already exists, do you want to override?',
            overrideCommentMessage: 'Adding PHPUnit config',
            skippedMessage: 'Skipped creation of PHPUnit config',
            sourceDirectory: Path::normalize(DirectoryPath::TEST->value),
            distDirectory: Path::normalize(DirectoryPath::DIST_TEST->value)
        );
    }
}
