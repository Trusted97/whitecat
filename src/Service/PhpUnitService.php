<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Exception\InvalidComposerException;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Helper\CopyHelper;

class PhpUnitService
{
    protected readonly string $workflowDirectoryPath;
    protected CopyHelper $copyHelper;
    protected ComposerHelper $composerHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs
    ) {
        $this->workflowDirectoryPath = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->copyHelper            = new CopyHelper($this->io, $this->fs);
        $this->composerHelper        = new ComposerHelper();
    }

    public function run(): int
    {
        $this->io->title('Setup basic PHPUnit');

        try {
            $composer = $this->composerHelper->getComposerContent(
                composerPath: './composer.json'
            );
        } catch (\JsonException $jsonException) {
            $errorMessage = \sprintf('Error: %s ', $jsonException->getMessage());
            $this->io->error($errorMessage);

            return Command::FAILURE;
        } catch (InvalidComposerException) {
            $this->io->error('Invalid composer.json file!');

            return Command::FAILURE;
        }

        $requireDev = $composer['require-dev'];

        $isInstalledPhpCsFixer = \array_key_exists('phpunit/phpunit', $requireDev);

        if (!$isInstalledPhpCsFixer) {
            $this->io->warning('It seems that phpunit/phpunit is not installed');
            $this->io->warning('Launch in terminal \'composer require --dev phpunit/phpunit\'');

            return Command::FAILURE;
        }

        $this->addTestDirectory();
        $this->addPHPUnitConfigFile();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CodeCoverageIgnore]
    private function addPHPUnitConfigFile(): void
    {
        $this->copyHelper->setupAndCopyFile(
            fileName: 'phpunit.xml',
            questionMessage: 'It seems that a phpunit.xml already exists, do you want to override?',
            overrideCommentMessage: 'Adding phpunit.xml',
            skippedMessage: 'Skipped creation of phpunit.xml',
            sourceFileDirectory: '',
            distFileDirectory: DirectoryPath::DIST_DIRECTORY->value
        );
    }

    #[CodeCoverageIgnore]
    private function addTestDirectory(): void
    {
        $this->copyHelper->setupAndCopyDirectory(
            questionMessage: 'It seems that config for PHPUnit already exists, do you want to override?',
            overrideCommentMessage: 'Adding PHPUnit config',
            skippedMessage: 'Skipped creation of PHPUnit config',
            sourceDirectory: Path::normalize(DirectoryPath::TEST->value),
            distDirectory: Path::normalize(DirectoryPath::DIST_TEST->value)
        );
    }
}
