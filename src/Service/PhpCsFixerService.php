<?php

namespace Whitecat\Service;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Helper\ComposerPackageChecker;
use Whitecat\Helper\FileCopyHelper;

class PhpCsFixerService
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
        $this->io->title('Setup basic php-cs-fixer');

        if (Command::SUCCESS !== $this->composerPackageChecker->ensureDevPackageInstalled($this->io, $this->workingDirectory, 'friendsofphp/php-cs-fixer')) {
            return Command::FAILURE;
        }

        $this->addPhpCsFixer();

        $this->io->success('Done. Your project files are ready.');

        return Command::SUCCESS;
    }

    protected function addPhpCsFixer(): void
    {
        $this->fileCopyHelper->copyFile(
            fileName: '.php-cs-fixer.dist.php',
            questionMessage: 'It seems that a .php-cs-fixer.dist.php already exists, do you want to override?',
            overrideCommentMessage: 'Adding .php-cs-fixer.dist.php',
            skippedMessage: 'Skipped creation of .php-cs-fixer.dist.php',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DIRECTORY->value
        );
    }
}
