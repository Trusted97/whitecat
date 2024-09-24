<?php

namespace Whitecat\Service;

use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Whitecat\Enums\DirectoryPath;
use Whitecat\Exception\InvalidComposerException;
use Whitecat\Helper\ComposerHelper;
use Whitecat\Helper\FileCopyHelper;

class PhpStanService
{
    protected readonly string $workflowDirectoryPath;
    protected FileCopyHelper $fileCopyHelper;
    protected ComposerHelper $composerHelper;

    public function __construct(
        protected readonly SymfonyStyle $io,
        protected readonly Filesystem $fs,
    ) {
        $this->workflowDirectoryPath = Path::normalize(DirectoryPath::WORKFLOW->value);
        $this->fileCopyHelper        = new FileCopyHelper($this->io, $this->fs);
        $this->composerHelper        = new ComposerHelper();
    }

    public function run(): int
    {
        $this->io->title('Setup basic PHPStan');

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

        $isInstalledPhpUnit = \array_key_exists('phpstan/phpstan', $requireDev);

        if (!$isInstalledPhpUnit) {
            $this->io->warning('It seems that phpstan/phpstan is not installed');
            $this->io->warning('Launch in terminal \'composer require --dev phpstan/phpstan\'');

            return Command::FAILURE;
        }

        $this->addPHPUnitConfigFile();

        $this->io->success('All work was correctly done!');

        return Command::SUCCESS;
    }

    #[CoversNothing]
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
