<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\ComposerPackageChecker;
use Whitecat\Helper\FileCopyHelper;

class PhpStanService
{
    protected readonly string $workflowDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;
    protected ComposerPackageChecker $composerPackageChecker;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
        protected readonly string $workingDirectory = '.',
    ) {
        $this->workflowDirectoryPath  = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->fileCopyHelper         = new FileCopyHelper($this->io, $this->fs, $this->workingDirectory);
        $this->composerPackageChecker = new ComposerPackageChecker();
    }

    public function run(): int
    {
        $this->io->title('Setup basic PHPStan');

        if (Command::SUCCESS !== $this->composerPackageChecker->ensureDevPackageInstalled($this->io, $this->workingDirectory, 'phpstan/phpstan')) {
            return Command::FAILURE;
        }

        $this->addPHPUnitConfigFile();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addPHPUnitConfigFile(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: 'phpstan.neon',
            questionMessage: 'It seems that a phpstan.neon already exists, do you want to override?',
            overrideCommentMessage: 'Adding phpstan.neon',
            skippedMessage: 'Skipped creation of phpstan.neon',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DIRECTORY->value
        );
    }
}
